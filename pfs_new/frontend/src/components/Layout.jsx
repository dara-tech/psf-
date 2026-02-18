import { useEffect, useState } from 'react';
import { useUIStore } from '../lib/stores/uiStore';
import { useAuthStore } from '../lib/store';
import { t } from '../lib/translations/index';
import api from '../lib/api';
import Sidebar from './Sidebar';
import ThemeToggle from './ThemeToggle';
import LanguageToggle from './LanguageToggle';
import FullscreenToggle from './FullscreenToggle';
import InstallAppButton from './InstallAppButton';
import { Button } from './ui/button';
import { SidebarProvider, useSidebar, SidebarInset } from './ui/sidebar';
import { FaBars } from 'react-icons/fa';

const SIDEBAR_COOKIE_NAME = 'sidebar_state';
function getDefaultSidebarOpen() {
  if (typeof document === 'undefined') return true;
  const match = document.cookie.match(new RegExp(`${SIDEBAR_COOKIE_NAME}=([^;]+)`));
  return match ? match[1] !== 'false' : true;
}

function LayoutContent({ children }) {
  const { initTheme, initLocale, locale } = useUIStore();
  const { token, setPermissions, setRoles } = useAuthStore();
  const { toggleSidebar } = useSidebar();

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
    <>
      <Sidebar />
      <SidebarInset>
        <div className="flex min-h-svh w-full flex-col bg-background">
          {/* Top bar */}
          <header className="sticky top-0 z-30 flex h-14 sm:h-16 items-center gap-2 sm:gap-4 border-b bg-background/95 backdrop-blur-xl supports-[backdrop-filter]:bg-background/60 px-3 sm:px-6 shadow-sm">
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
          <main className="flex-1 p-3 sm:p-4 lg:p-6">
            {children}
          </main>
        </div>
      </SidebarInset>
    </>
  );
}

export default function Layout({ children }) {
  const [defaultOpen] = useState(getDefaultSidebarOpen);
  return (
    <SidebarProvider defaultOpen={defaultOpen}>
      <LayoutContent>{children}</LayoutContent>
    </SidebarProvider>
  );
}

