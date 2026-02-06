// PWA Service Worker Registration Helper
// This file handles PWA registration with proper error handling

export async function registerPWA() {
  if (typeof window === 'undefined' || !('serviceWorker' in navigator)) {
    return;
  }

  try {
    // Dynamically import PWA register - only available when vite-plugin-pwa is loaded
    const { registerSW } = await import('virtual:pwa-register');
    
    const updateSW = registerSW({
      onNeedRefresh() {
        // Show update notification
        if (confirm('New version available! Reload to update?')) {
          updateSW(true);
        }
      },
      onOfflineReady() {
        console.log('App ready to work offline');
      },
      onRegistered(registration) {
        console.log('Service Worker registered:', registration);
      },
      onRegisterError(error) {
        console.error('Service Worker registration error:', error);
      },
    });
  } catch (error) {
    // PWA register not available - this is fine in dev mode or if plugin not configured
    if (import.meta.env.DEV) {
      console.log('PWA service worker registration skipped (dev mode)');
    } else {
      console.warn('PWA service worker registration failed:', error);
    }
  }
}
