import express from 'express';
import cors from 'cors';
import helmet from 'helmet';
import morgan from 'morgan';
import session from 'express-session';
import dotenv from 'dotenv';
import { sequelize } from './config/database.js';
import authRoutes from './routes/auth.js';
import adminRoutes from './routes/admin.js';
import reportingRoutes from './routes/reporting.js';
import apiRoutes from './routes/api.js';
import questionnaireRoutes from './routes/questionnaire.js';
import ttsRoutes from './routes/tts.js';

dotenv.config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(helmet());
app.use(morgan('dev'));
app.use(cors({
  origin: [
    process.env.CLIENT_URL || 'http://localhost:5173',  // Web frontend
    'http://localhost:3000',                            // Backend port
    'http://192.168.1.116:3000',                       // Mobile app (physical device)
    'http://10.0.2.2:3000',                             // Android emulator
    /^http:\/\/localhost:\d+$/,                         // Allow localhost with any port
    /^http:\/\/192\.168\.\d+\.\d+:\d+$/,               // Allow any local network IP
    /^http:\/\/10\.0\.2\.\d+:\d+$/,                     // Allow Android emulator IPs
  ],
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

// Health check
app.get('/api/health', (req, res) => {
  res.json({ status: 'ok', timestamp: new Date().toISOString() });
});

// Database connection
sequelize.authenticate()
  .then(() => {
    console.log('✅ Database connected successfully');
    app.listen(PORT, () => {
      console.log(`🚀 Server running on port ${PORT}`);
    });
  })
  .catch(err => {
    console.error('❌ Unable to connect to database:', err);
  });

export default app;

