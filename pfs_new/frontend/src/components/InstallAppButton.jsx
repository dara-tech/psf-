import { useState, useEffect } from 'react';
import { Button } from './ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from './ui/dialog';
import { FaDownload, FaInfoCircle } from 'react-icons/fa';
import { useUIStore } from '../lib/stores/uiStore';
import { t } from '../lib/translations/index';

export default function InstallAppButton() {
  const { locale } = useUIStore();
  const [deferredPrompt, setDeferredPrompt] = useState(null);
  const [isInstalled, setIsInstalled] = useState(false);
  const [showSafariDialog, setShowSafariDialog] = useState(false);

  useEffect(() => {
    // Check if app is already installed
    const checkInstalled = () => {
      const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
      const isIOSStandalone = window.navigator.standalone === true;
      return isStandalone || isIOSStandalone;
    };

    if (checkInstalled()) {
      setIsInstalled(true);
      return;
    }

    // Check if service worker is registered (indicates PWA is available)
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.getRegistration().then(registration => {
        if (registration) {
          console.log('Service worker registered - PWA available');
        }
      });
    }

    // Listen for the beforeinstallprompt event
    const handleBeforeInstallPrompt = (e) => {
      // Prevent the mini-infobar from appearing on mobile
      e.preventDefault();
      // Stash the event so it can be triggered later
      setDeferredPrompt(e);
      console.log('PWA install prompt available');
    };

    // Listen for app installed event
    const handleAppInstalled = () => {
      setIsInstalled(true);
      setDeferredPrompt(null);
      console.log('App installed');
    };

    window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
    window.addEventListener('appinstalled', handleAppInstalled);

    // Re-check periodically in case status changes
    const intervalId = setInterval(() => {
      if (checkInstalled()) {
        setIsInstalled(true);
      }
    }, 1000);

    return () => {
      window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
      window.removeEventListener('appinstalled', handleAppInstalled);
      clearInterval(intervalId);
    };
  }, []);

  // Detect browser and platform
  const detectBrowser = () => {
    const userAgent = navigator.userAgent;
    const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
    const isIOS = /iPad|iPhone|iPod/.test(userAgent);
    const isSafari = /^((?!chrome|android).)*safari/i.test(userAgent);
    const isChrome = /chrome/i.test(userAgent) && !/edg/i.test(userAgent);
    const isEdge = /edg/i.test(userAgent);
    
    return { isMac, isIOS, isSafari, isChrome, isEdge };
  };

  const handleInstallClick = async (e) => {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    
    console.log('Install button clicked');
    
    const { isMac, isIOS, isSafari } = detectBrowser();
    console.log('Browser detection:', { isMac, isIOS, isSafari, deferredPrompt: !!deferredPrompt });
    
    // Always show dialog first for Safari or if no prompt available
    // Safari on Mac or iOS doesn't support beforeinstallprompt
    if ((isMac && isSafari) || (isIOS && isSafari)) {
      console.log('Safari detected - showing instructions dialog');
      setShowSafariDialog(true);
      return;
    }

    // If no deferred prompt, show instructions dialog
    if (!deferredPrompt) {
      console.log('No deferred prompt available - showing instructions dialog');
      setShowSafariDialog(true);
      return;
    }

    // Show the install prompt (Chrome/Edge on Mac/Windows)
    try {
      console.log('Showing install prompt...');
      await deferredPrompt.prompt();

      // Wait for the user to respond to the prompt
      const { outcome } = await deferredPrompt.userChoice;
      console.log('Install prompt outcome:', outcome);

      if (outcome === 'accepted') {
        console.log('User accepted the install prompt');
      } else {
        console.log('User dismissed the install prompt');
      }
    } catch (error) {
      console.error('Error showing install prompt:', error);
      // Fallback to instructions dialog
      setShowSafariDialog(true);
    }

    // Clear the deferredPrompt
    setDeferredPrompt(null);
  };

  // Don't show button if already installed
  if (isInstalled) {
    return null;
  }

  const { isMac, isIOS, isSafari } = detectBrowser();
  const isSafariBrowser = (isMac && isSafari) || (isIOS && isSafari);

  // Show button (will be visible unless app is installed)
  return (
    <>
      <Button
        type="button"
        onClick={handleInstallClick}
        variant="outline"
        size="sm"
        className="gap-2 border-primary/20 hover:border-primary/40 hover:bg-primary/5 cursor-pointer"
        title={locale === 'kh' ? 'ដំឡើងកម្មវិធី' : 'Install App'}
      >
        <FaDownload className="h-3.5 w-3.5" />
        <span className="hidden sm:inline">
          {locale === 'kh' ? 'ដំឡើង' : 'Install'}
        </span>
      </Button>

      {/* Safari Installation Instructions Dialog */}
      <Dialog open={showSafariDialog} onOpenChange={setShowSafariDialog}>
        <DialogContent className="sm:max-w-[500px]">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <FaInfoCircle className="h-5 w-5 text-primary" />
              {locale === 'kh' ? 'ដំឡើងកម្មវិធី' : 'Install App'}
            </DialogTitle>
            <DialogDescription>
              {isSafariBrowser
                ? (locale === 'kh' 
                    ? 'សូមធ្វើតាមជំហានទាំងនេះដើម្បីដំឡើងកម្មវិធី'
                    : 'Follow these steps to install the app')
                : (locale === 'kh'
                    ? 'សូមធ្វើតាមជំហានទាំងនេះដើម្បីដំឡើងកម្មវិធី'
                    : 'Follow these steps to install the app')}
            </DialogDescription>
          </DialogHeader>
          
          <div className="space-y-4 py-4">
            {isMac && isSafari ? (
              // Safari on Mac instructions
              <div className="space-y-3">
                <div className="flex gap-3">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    1
                  </div>
                  <div className="flex-1">
                    <p className="font-medium">
                      {locale === 'kh' 
                        ? 'ចុចលើប៊ូតុង Share (បែែបែប) នៅក្នុង Safari toolbar'
                        : 'Click the Share button in Safari toolbar'}
                    </p>
                    <p className="text-sm text-muted-foreground mt-1">
                      {locale === 'kh'
                        ? 'រូបសញ្ញាបែែបែបនៅខាងស្តាំនៃ address bar'
                        : 'The share icon on the right side of the address bar'}
                    </p>
                  </div>
                </div>
                
                <div className="flex gap-3">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    2
                  </div>
                  <div className="flex-1">
                    <p className="font-medium">
                      {locale === 'kh'
                        ? 'ជ្រើស "Add to Dock"'
                        : 'Select "Add to Dock"'}
                    </p>
                    <p className="text-sm text-muted-foreground mt-1">
                      {locale === 'kh'
                        ? 'ឬអាចជ្រើស "Add to Desktop"'
                        : 'Or select "Add to Desktop"'}
                    </p>
                  </div>
                </div>

                <div className="flex gap-3">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    3
                  </div>
                  <div className="flex-1">
                    <p className="font-medium">
                      {locale === 'kh'
                        ? 'កម្មវិធីនឹងបង្ហាញនៅក្នុង Dock ឬ Desktop'
                        : 'The app will appear in your Dock or Desktop'}
                    </p>
                  </div>
                </div>

                <div className="mt-4 p-3 bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-800 rounded-lg">
                  <p className="text-sm text-amber-800 dark:text-amber-200">
                    <strong>{locale === 'kh' ? 'ចំណាំ:' : 'Note:'}</strong>{' '}
                    {locale === 'kh'
                      ? 'Safari នៅលើ Mac មានការគាំទ្រ PWA តិចតួច។ សូមប្រើ Chrome ឬ Edge browser ដើម្បីទទួលបានបទពិសោធន៍ល្អបំផុត។'
                      : 'Safari on Mac has limited PWA support. For the best experience, please use Chrome or Edge browser.'}
                  </p>
                </div>
              </div>
            ) : isIOS && isSafari ? (
              // iOS Safari instructions
              <div className="space-y-3">
                <div className="flex gap-3">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    1
                  </div>
                  <div className="flex-1">
                    <p className="font-medium">
                      {locale === 'kh'
                        ? 'ចុចលើប៊ូតុង Share (បែែបែប)'
                        : 'Tap the Share button (square with arrow)'}
                    </p>
                    <p className="text-sm text-muted-foreground mt-1">
                      {locale === 'kh'
                        ? 'នៅខាងក្រោមអេក្រង់'
                        : 'At the bottom of the screen'}
                    </p>
                  </div>
                </div>
                
                <div className="flex gap-3">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    2
                  </div>
                  <div className="flex-1">
                    <p className="font-medium">
                      {locale === 'kh'
                        ? 'ជ្រើស "Add to Home Screen"'
                        : 'Select "Add to Home Screen"'}
                    </p>
                  </div>
                </div>

                <div className="flex gap-3">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    3
                  </div>
                  <div className="flex-1">
                    <p className="font-medium">
                      {locale === 'kh'
                        ? 'ចុច "Add" (បន្ថែម)'
                        : 'Tap "Add"'}
                    </p>
                  </div>
                </div>
              </div>
            ) : (
              // General instructions for other browsers
              <div className="space-y-3">
                <div className="flex gap-3">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    1
                  </div>
                  <div className="flex-1">
                    <p className="font-medium">
                      {locale === 'kh'
                        ? 'រកមើលរូបសញ្ញា Install ឬ Download'
                        : 'Look for the Install or Download icon'}
                    </p>
                    <p className="text-sm text-muted-foreground mt-1">
                      {locale === 'kh'
                        ? 'នៅក្នុង address bar'
                        : 'In the address bar'}
                    </p>
                  </div>
                </div>
                
                <div className="flex gap-3">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    2
                  </div>
                  <div className="flex-1">
                    <p className="font-medium">
                      {locale === 'kh'
                        ? 'ចុចលើវា ហើយធ្វើតាមការណែនាំ'
                        : 'Click on it and follow the prompts'}
                    </p>
                  </div>
                </div>

                <div className="mt-4 p-3 bg-muted rounded-lg">
                  <p className="text-sm text-muted-foreground">
                    {locale === 'kh'
                      ? 'ប្រសិនបើអ្នកមិនឃើញជម្រើសនេះទេ សូមពិនិត្យមើល browser settings ឬប្រើ Chrome/Edge browser។'
                      : 'If you don\'t see this option, check your browser settings or try Chrome/Edge browser.'}
                  </p>
                </div>
              </div>
            )}
          </div>

          <DialogFooter>
            <Button onClick={() => setShowSafariDialog(false)}>
              {locale === 'kh' ? 'យល់ព្រម' : 'Got it'}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
}
