import express from 'express';
import cors from 'cors';
import helmet from 'helmet';
import morgan from 'morgan';
import session from 'express-session';
import dotenv from 'dotenv';
import path from 'path';
import { fileURLToPath } from 'url';
import { sequelize } from './config/database.js';
import authRoutes from './routes/auth.js';
import adminRoutes from './routes/admin.js';
import reportingRoutes from './routes/reporting.js';
import apiRoutes from './routes/api.js';
import questionnaireRoutes from './routes/questionnaire.js';
import ttsRoutes from './routes/tts.js';
import settingsRoutes from './routes/settings.js';
import downloadsRoutes from './routes/downloads.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

dotenv.config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
// Configure Helmet for API server (less restrictive for mobile apps)
app.use(helmet({
  contentSecurityPolicy: false, // Disable CSP for API endpoints
  crossOriginEmbedderPolicy: false, // Allow cross-origin requests
  crossOriginResourcePolicy: { policy: "cross-origin" } // Allow cross-origin resources
}));
app.use(morgan('dev'));

// CORS configuration - allow mobile apps and web frontend
app.use(cors({
  origin: (origin, callback) => {
    // Allow requests with no origin (mobile apps, Postman, etc.)
    if (!origin) {
      return callback(null, true);
    }
    
    const allowedOrigins = [
      process.env.CLIENT_URL || 'http://localhost:5173',  // Web frontend
      'http://localhost:3000',                            // Backend port
      'http://107.175.91.211',                            // Production frontend (port 80)
      'http://107.175.91.211:80',                         // Production frontend (explicit port 80)
      'http://107.175.91.211:3000',                      // Production backend
      'https://107.175.91.211',                           // Production frontend (HTTPS)
      'https://107.175.91.211:443',                       // Production frontend (HTTPS explicit)
      'http://192.168.1.116:3000',                       // Mobile app (physical device - old IP)
      'http://192.168.0.102:3000',                       // Mobile app (physical device - current IP)
      'http://10.0.2.2:3000',                             // Android emulator
    ];
    
    // Check exact matches
    if (allowedOrigins.includes(origin)) {
      return callback(null, true);
    }
    
    // Check regex patterns
    const patterns = [
      /^http:\/\/localhost:\d+$/,                         // Allow localhost with any port
      /^https:\/\/localhost:\d+$/,                        // Allow localhost HTTPS with any port
      /^http:\/\/192\.168\.\d+\.\d+:\d+$/,               // Allow any local network IP
      /^http:\/\/10\.0\.2\.\d+:\d+$/,                     // Allow Android emulator IPs
      /^http:\/\/107\.175\.91\.211(:\d+)?$/,             // Allow production server (with or without port)
      /^https:\/\/107\.175\.91\.211(:\d+)?$/,            // Allow production server HTTPS (with or without port)
    ];
    
    if (patterns.some(pattern => pattern.test(origin))) {
      return callback(null, true);
    }
    
    // Allow all origins in development (for easier testing)
    if (process.env.NODE_ENV === 'development') {
      return callback(null, true);
    }
    
    callback(new Error('Not allowed by CORS'));
  },
  credentials: true,
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With']
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Session configuration
app.use(session({
  secret: process.env.SESSION_SECRET || 'your-secret-key',
  resave: false,
  saveUninitialized: false,
  cookie: {
    secure: process.env.NODE_ENV === 'production',
    httpOnly: true,
    maxAge: 24 * 60 * 60 * 1000 // 24 hours
  }
}));

// Routes
app.use('/api/auth', authRoutes);
app.use('/api/admin', adminRoutes);
app.use('/api/reporting', reportingRoutes);
app.use('/api/v1', apiRoutes);
app.use('/api/questionnaire', questionnaireRoutes);
app.use('/api/tts', ttsRoutes);
app.use('/api/settings', settingsRoutes);
app.use('/api/downloads', downloadsRoutes);

// Serve APK files from public directory (static files)
app.use('/downloads', express.static(path.join(__dirname, '../public/downloads')));

// Health check
app.get('/api/health', (req, res) => {
  res.json({ status: 'ok', timestamp: new Date().toISOString() });
});

// Global error handler middleware (must be after all routes)
app.use((err, req, res, next) => {
  console.error('❌ Unhandled error:', err);
  console.error('❌ Error stack:', err.stack);
  console.error('❌ Request URL:', req.originalUrl);
  console.error('❌ Request method:', req.method);
  
  // Don't send response if headers already sent
  if (res.headersSent) {
    return next(err);
  }
  
  // Default error status
  const status = err.status || err.statusCode || 500;
  
  // Send error response
  res.status(status).json({
    error: err.message || 'Internal server error',
    details: process.env.NODE_ENV === 'development' ? err.stack : undefined,
    path: req.originalUrl,
    method: req.method
  });
});

// 404 handler (must be after all routes and error handler)
app.use((req, res) => {
  res.status(404).json({
    error: 'Route not found',
    path: req.originalUrl,
    method: req.method
  });
});

// Database connection
sequelize.authenticate()
  .then(() => {
    console.log('✅ Database connected successfully');
    app.listen(PORT, '0.0.0.0', () => {
      console.log(`🚀 Server running on port ${PORT}`);
      console.log(`🌐 Accessible at http://localhost:${PORT} and http://192.168.1.116:${PORT}`);
    });
  })
  .catch(err => {
    console.error('❌ Unable to connect to database:', err);
  });

export default app;

