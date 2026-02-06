# PWA Setup Guide

This app is configured as a Progressive Web App (PWA) with offline support.

## Features

- ✅ Installable on mobile devices and desktop
- ✅ Works offline with automatic data queuing
- ✅ Automatic sync when connection is restored
- ✅ Cached API responses for faster loading
- ✅ Service worker for background updates

## Icon Generation

You need to create PWA icons in the `public/icons/` directory. Required sizes:

- `icon-72x72.png`
- `icon-96x96.png`
- `icon-128x128.png`
- `icon-144x144.png`
- `icon-152x152.png`
- `icon-192x192.png` (required, maskable)
- `icon-384x384.png`
- `icon-512x512.png` (required, maskable)

### Quick Icon Generation

1. Create a 512x512px icon image (PNG format)
2. Use an online tool like:
   - https://www.pwabuilder.com/imageGenerator
   - https://realfavicongenerator.net/
   - https://www.favicon-generator.org/

3. Or use ImageMagick/GraphicsMagick:
```bash
# Create icons directory
mkdir -p public/icons

# Generate all sizes from a 512x512 source image
convert source-icon.png -resize 72x72 public/icons/icon-72x72.png
convert source-icon.png -resize 96x96 public/icons/icon-96x96.png
convert source-icon.png -resize 128x128 public/icons/icon-128x128.png
convert source-icon.png -resize 144x144 public/icons/icon-144x144.png
convert source-icon.png -resize 152x152 public/icons/icon-152x152.png
convert source-icon.png -resize 192x192 public/icons/icon-192x192.png
convert source-icon.png -resize 384x384 public/icons/icon-384x384.png
convert source-icon.png -resize 512x512 public/icons/icon-512x512.png
```

## Installation

1. Install dependencies:
```bash
npm install
```

2. Build the app:
```bash
npm run build
```

3. The service worker will be automatically generated in the `dist` folder

## Testing PWA Features

### Development
- PWA is enabled in dev mode
- Service worker runs on localhost
- Test offline mode using Chrome DevTools: Application > Service Workers > Offline

### Production
- Deploy the `dist` folder to your server
- Ensure HTTPS is enabled (required for PWA)
- Test installation on mobile devices

## Offline Functionality

### How It Works

1. **Data Collection**: When offline, form submissions are queued locally
2. **Auto-Sync**: When connection is restored, queued data syncs automatically
3. **Caching**: GET requests are cached for offline access
4. **Indicator**: Offline indicator shows connection status and pending submissions

### Testing Offline Mode

1. Open Chrome DevTools (F12)
2. Go to Network tab
3. Select "Offline" from throttling dropdown
4. Try submitting a form - it should queue
5. Go back online - it should sync automatically

## Browser Support

- ✅ Chrome/Edge (Android & Desktop)
- ✅ Safari (iOS 11.3+)
- ✅ Firefox (Android)
- ⚠️ Some features may vary by browser

## Troubleshooting

### Service Worker Not Registering
- Ensure you're using HTTPS (or localhost)
- Check browser console for errors
- Clear browser cache and reload

### Icons Not Showing
- Verify icons exist in `public/icons/` directory
- Check file names match exactly (case-sensitive)
- Ensure icons are PNG format

### Offline Mode Not Working
- Check browser supports service workers
- Verify service worker is registered (DevTools > Application > Service Workers)
- Check offline store is persisting (DevTools > Application > Local Storage)

## Next Steps

1. Create and add PWA icons
2. Test on real mobile devices
3. Configure push notifications (optional)
4. Set up background sync (optional)
