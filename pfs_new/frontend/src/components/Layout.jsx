import { useEffect } from 'react';
import { useUIStore } from '../lib/stores/uiStore';
import { useAuthStore } from '../lib/store';
import { t } from '../lib/translations/index';
import api from '../lib/api';
import { cn } from '../lib/utils';
import Sidebar from './Sidebar';
import ThemeToggle from './ThemeToggle';
import LanguageToggle from './LanguageToggle';
import FullscreenToggle from './FullscreenToggle';
import InstallAppButton from './InstallAppButton';
import { Button } from './ui/button';
import { FaBars } from 'react-icons/fa';

export default function Layout({ children }) {
  const { sidebarOpen, toggleSidebar, sidebarCollapsed, initTheme, initLocale, locale } = useUIStore();
  const { token, setPermissions, setRoles } = useAuthStore();

  // Initialize theme and locale on mount
  useEffect(() => {
    initTheme();
    initLocale();
  }, [initTheme, initLocale]);

  // Fetch user permissions on mount if not already set
  useEffect(() => {
    if (token && !useAuthStore.getState().permissions.length) {
      api.get('/auth/me')
        .then(response => {
          if (response.data.permissions) {
            setPermissions(response.data.permissions);
          }
          if (response.data.roles) {
            setRoles(response.data.roles);
          }
        })
        .catch(error => {
          console.error('Failed to fetch user permissions:', error);
        });
    }
  }, [token, setPermissions, setRoles]);

  return (
    <div className="min-h-screen bg-background">
      <Sidebar />
      
      {/* Main content */}
      <div
        className={cn(
          'transition-all duration-300 ease-in-out',
          sidebarCollapsed ? 'lg:ml-16' : 'lg:ml-64'
        )}
      >
        {/* Top bar */}
        <header className="sticky top-0 z-30 flex h-14 sm:h-16 items-center gap-2 sm:gap-4 border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 px-3 sm:px-6 shadow-sm">
          <Button
            variant="ghost"
            size="icon"
            onClick={toggleSidebar}
            className="lg:hidden min-h-[44px] min-w-[44px] touch-manipulation"
          >
            <FaBars className="h-5 w-5" />
          </Button>
          <div className="flex-1" />
          <div className="flex items-center gap-2 sm:gap-4">
            <InstallAppButton />
            <div className="h-6 sm:h-8 w-px bg-border hidden sm:block" />
            <LanguageToggle />
            <div className="h-6 sm:h-8 w-px bg-border hidden sm:block" />
            <ThemeToggle />
            <div className="h-6 sm:h-8 w-px bg-border hidden sm:block" />
            <FullscreenToggle />
            <div className="h-6 sm:h-8 w-px bg-border hidden sm:block" />
            <div className="flex items-center gap-1.5 sm:gap-2">
              <div className="h-2 w-2 rounded-full bg-green-500 animate-pulse" />
              <span className="text-xs sm:text-sm text-muted-foreground hidden sm:inline">{t(locale, 'admin.dashboard.online')}</span>
            </div>
          </div>
        </header>

        {/* Page content */}
        <main className="p-3 sm:p-4 lg:p-6">
          {children}
        </main>
      </div>
    </div>
  );
}

