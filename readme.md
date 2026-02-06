# PSF Health Survey and Reporting System

A full-stack application for health survey data collection and reporting, consisting of:
- **Backend**: Node.js/Express API
- **Frontend**: React/Vite web application
- **Mobile**: React Native/Expo mobile app

## 🚀 Quick Start

### Prerequisites
- Node.js 20+
- MySQL 8+
- npm or yarn

### Backend Setup

```bash
cd pfs_new/backend
cp .env.example .env
# Edit .env with your database credentials
npm install
npm start
```

### Frontend Setup

```bash
cd pfs_new/frontend
npm install
npm run dev
```

### Mobile App Setup

```bash
cd pfs_new/mobile-expo
npm install
# Set EXPO_PUBLIC_API_URL in .env
npx expo start
```

## 📦 Deployment

See [pfs_new/DEPLOY.md](pfs_new/DEPLOY.md) for deployment instructions.

### Quick Deploy

```bash
cd pfs_new
./deploy.sh all
```

## 🌐 Production URLs

- **Frontend**: http://107.175.91.211
- **Backend API**: http://107.175.91.211:3000
- **API Health**: http://107.175.91.211/api/health

## 📁 Project Structure

```
pfs_new/
├── backend/          # Node.js/Express API
│   ├── src/
│   │   ├── app.js
│   │   ├── config/
│   │   ├── controllers/
│   │   ├── models/
│   │   └── routes/
│   └── migrations/
├── frontend/         # React/Vite web app
│   ├── src/
│   └── dist/        # Built files (production)
└── mobile-expo/      # React Native mobile app
```

## 🔐 Security Notes

- `.env` files are excluded from git
- Large SQL files (`psf.sql`) should be uploaded separately to VPS
- Google Cloud credentials (`photoai-*.json`) are excluded from git
- Never commit sensitive credentials

## 📝 License

[Add your license here]

## 👥 Contributors

[Add contributors here]
