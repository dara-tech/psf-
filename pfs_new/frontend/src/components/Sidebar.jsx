import { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuthStore } from '../lib/store';
import { useUIStore } from '../lib/stores/uiStore';
import { t } from '../lib/translations/index';
import api from '../lib/api';
import { cn } from '../lib/utils';
import {
  PiFilesFill,
  PiChartLineFill,
  PiUsersFill,
  PiShieldFill,
  PiBriefcaseFill,
  PiBuildingsFill,
  PiSignOut,
  PiCaretRight,
  PiCaretDown,
  PiList,
  PiX,
  PiQuestionFill,
  PiGearFill,
  PiGraphFill,
  PiQrCode,
} from 'react-icons/pi';
import { Button } from './ui/button';
import { ScrollArea } from './ui/scroll-area';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from './ui/dialog';
import { ColorPicker } from './ui/color-picker';
import { FaPalette } from 'react-icons/fa';

// Sidebar styling constants
const SIDEBAR_STYLES = {
  padding: {
    menuItem: 'px-3 py-2.5',
    menuItemCollapsed: 'py-2.5',
    submenuItem: 'px-3 py-2',
    submenuSection: 'px-3 py-1.5',
    tooltip: 'px-3 py-1.5',
    container: 'p-3',
    header: 'px-4',
    footer: 'p-3',
    userCard: 'px-3 py-2.5',
  },
  rounded: {
    menuItem: 'rounded-lg',
    submenuCard: 'rounded-lg',
    tooltip: 'rounded-lg',
    logo: 'rounded-lg',
    userCard: 'rounded-lg',
  },
};

// Menu items structure - titles will be translated dynamically
const getMenuItems = (locale) => [
  {
    title: t(locale, 'admin.common.reporting'),
    icon: PiFilesFill,
    children: [
      {
        title: t(locale, 'admin.common.patient'),
        children: [
          { title: t(locale, 'admin.common.dashboard'), path: '/patients', icon: PiChartLineFill },
          { title: t(locale, 'admin.common.export'), path: '/reporting', icon: PiFilesFill, permission: 'users_manage' },
          { title: t(locale, 'admin.common.surveyAnalysis'), path: '/survey-analysis', icon: PiGraphFill, permission: 'users_manage' },
        ],
      },
      {
        title: t(locale, 'admin.common.hfs'),
        children: [
          { title: t(locale, 'admin.common.dashboard'), path: '/hfs_dashboard', icon: PiChartLineFill },
          { title: t(locale, 'admin.common.export'), path: '/hfs', icon: PiFilesFill, permission: 'users_manage' },
        ],
      },
      {
        title: t(locale, 'admin.common.adminDashboard'),
        path: '/admin_dashboard',
        icon: PiChartLineFill,
        permission: 'users_manage',
      },
    ],
  },
  {
    title: t(locale, 'admin.common.userManagement'),
    icon: PiUsersFill,
    permission: 'users_manage',
    children: [
      { title: t(locale, 'admin.dashboard.permissions'), path: '/permissions', icon: PiShieldFill },
      { title: t(locale, 'admin.dashboard.roles'), path: '/roles', icon: PiBriefcaseFill },
      { title: t(locale, 'admin.dashboard.sites'), path: '/sites', icon: PiBuildingsFill },
      { title: t(locale, 'admin.users.title'), path: '/users', icon: PiUsersFill },
      { title: t(locale, 'admin.questions.title'), path: '/questions', icon: PiQuestionFill },
      { title: locale === 'kh' ? 'QR Code' : 'QR Codes', path: '/qr-codes', icon: PiQrCode, permission: 'users_manage' },
    ],
  },
  {
    title: t(locale, 'admin.common.settings'),
    path: '/settings',
    icon: PiGearFill,
  },
];

function MenuItem({ item, level = 0, collapsed = false }) {
  const location = useLocation();
  const { hasPermission } = useAuthStore();
  const [open, setOpen] = useState(false);
  const [hovered, setHovered] = useState(false);

  const isActive = item.path && location.pathname === item.path;
  const hasActiveChild = item.children?.some(child => {
    if (child.path) return location.pathname === child.path;
    if (child.children) {
      return child.children.some(grandchild => grandchild.path === location.pathname);
    }
    return false;
  });
  const hasChildren = item.children && item.children.length > 0;
  const Icon = item.icon;

  // Auto-open parent if child is active - must be called unconditionally
  useEffect(() => {
    if (hasChildren && !collapsed && hasActiveChild) {
      setOpen(true);
    }
  }, [location.pathname, hasChildren, collapsed, hasActiveChild]);

  // Check permission - must be after hooks
  if (item.permission && !hasPermission(item.permission)) {
    return null;
  }

  if (hasChildren) {
    return (
      <div 
        className="relative group"
        onMouseEnter={() => setHovered(true)}
        onMouseLeave={() => {
          if (!open) {
            setHovered(false);
          }
        }}
      >
        {collapsed ? (
          <button
            onClick={() => setOpen(!open)}
            className={cn(
              'w-full flex items-center justify-center transition-all duration-200',
              SIDEBAR_STYLES.padding.menuItemCollapsed,
              SIDEBAR_STYLES.rounded.menuItem,
              'hover:bg-accent/50',
              (isActive || hasActiveChild) && 'bg-primary/10',
              (isActive || hasActiveChild) && 'text-primary',
              !(isActive || hasActiveChild) && 'text-muted-foreground'
            )}
            title={item.title}
          >
            {Icon && <Icon className="h-5 w-5" />}
          </button>
        ) : (
          <button
            onClick={() => setOpen(!open)}
            className={cn(
              'w-full flex items-center justify-between text-sm font-medium transition-all duration-200',
              SIDEBAR_STYLES.padding.menuItem,
              SIDEBAR_STYLES.rounded.menuItem,
              'hover:bg-accent/50',
              (isActive || hasActiveChild) && 'bg-primary/10 text-primary',
              !(isActive || hasActiveChild) && 'text-muted-foreground',
              level > 0 && 'pl-6'
            )}
          >
            <div className="flex items-center gap-3">
              {Icon && <Icon className="h-5 w-5 flex-shrink-0" />}
              <span>{item.title}</span>
            </div>
            <PiCaretRight className={cn(
              'h-3.5 w-3.5 transition-transform duration-200 text-muted-foreground/60',
              open && 'rotate-90'
            )} />
          </button>
        )}
        
        {/* Submenu card for collapsed state */}
        {collapsed && (hovered || open) && (
          <div 
            className={cn(
              'absolute left-full ml-3 top-0 bg-card border border-border/50 shadow-xl z-50 min-w-[220px] py-2 animate-in fade-in-0 slide-in-from-left-2 duration-200',
              SIDEBAR_STYLES.rounded.submenuCard
            )}
            onMouseEnter={() => setHovered(true)}
            onMouseLeave={() => {
              if (!open) {
                setHovered(false);
              }
            }}
          >
            {item.children.map((child, idx) => {
              if (child.children) {
                return (
                  <div key={idx} className={SIDEBAR_STYLES.padding.submenuSection}>
                    <div className="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-2">
                      {child.title}
                    </div>
                    <div className="space-y-0.5">
                      {child.children.map((grandchild, gIdx) => {
                        const grandchildActive = grandchild.path === location.pathname;
                        return (
                          <Link
                            key={gIdx}
                            to={grandchild.path}
                            className={cn(
                              'flex items-center gap-3 text-sm font-medium transition-all duration-200',
                              SIDEBAR_STYLES.padding.submenuItem,
                              SIDEBAR_STYLES.rounded.menuItem,
                              grandchildActive
                                ? 'bg-primary text-primary-foreground'
                                : 'hover:bg-accent/50 text-muted-foreground'
                            )}
                          >
                            {grandchild.icon && <grandchild.icon className="h-4 w-4 flex-shrink-0" />}
                            <span>{grandchild.title}</span>
                          </Link>
                        );
                      })}
                    </div>
                  </div>
                );
              }
              const childActive = child.path === location.pathname;
              return (
                <Link
                  key={idx}
                  to={child.path}
                  className={cn(
                    'flex items-center gap-3 mx-2 text-sm font-medium transition-all duration-200',
                    SIDEBAR_STYLES.padding.submenuItem,
                    SIDEBAR_STYLES.rounded.menuItem,
                    childActive
                      ? 'bg-primary text-primary-foreground'
                      : 'hover:bg-accent/50 text-muted-foreground'
                  )}
                >
                  {child.icon && <child.icon className="h-4 w-4 flex-shrink-0" />}
                  <span>{child.title}</span>
                </Link>
              );
            })}
          </div>
        )}

        {/* Expanded children */}
        {open && !collapsed && (
          <div className="space-y-0.5 mt-1 ml-2 border-l border-border/30 pl-4">
            {item.children.map((child, idx) => (
              <MenuItem key={idx} item={child} level={level + 1} collapsed={collapsed} />
            ))}
          </div>
        )}
      </div>
    );
  }

  return (
    <div 
      className="relative group"
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
    >
      {collapsed ? (
        <Link
          to={item.path}
          className={cn(
            'w-full flex items-center justify-center transition-all duration-200',
            SIDEBAR_STYLES.padding.menuItemCollapsed,
            SIDEBAR_STYLES.rounded.menuItem,
            'hover:bg-accent/50',
            isActive && 'bg-primary text-primary-foreground',
            !isActive && 'text-muted-foreground'
          )}
          title={item.title}
        >
          {Icon && <Icon className="h-5 w-5" />}
        </Link>
      ) : (
        <Link
          to={item.path}
          className={cn(
            'flex items-center gap-3 text-sm font-medium transition-all duration-200',
            SIDEBAR_STYLES.padding.menuItem,
            SIDEBAR_STYLES.rounded.menuItem,
            isActive
              ? 'bg-primary text-primary-foreground'
              : 'hover:bg-accent/50 text-muted-foreground',
            level > 0 && 'pl-6'
          )}
        >
          {Icon && <Icon className="h-5 w-5 flex-shrink-0" />}
          <span>{item.title}</span>
        </Link>
      )}
      
      {/* Simple tooltip for collapsed state (no children) */}
      {collapsed && hovered && !hasChildren && (
        <div className={cn(
          'absolute left-full ml-3 top-1/2 -translate-y-1/2 bg-popover border border-border/50 shadow-lg z-50 whitespace-nowrap animate-in fade-in-0 slide-in-from-left-2 duration-200',
          SIDEBAR_STYLES.padding.tooltip,
          SIDEBAR_STYLES.rounded.tooltip
        )}>
          <span className="text-sm font-semibold text-muted-foreground">{item.title}</span>
        </div>
      )}
    </div>
  );
}

// Color palette options
const COLOR_PALETTES = [
  { name: 'green', color: 'hsl(142, 76%, 36%)', label: 'Green' },
  { name: 'blue', color: 'hsl(217, 91%, 60%)', label: 'Blue' },
  { name: 'purple', color: 'hsl(262, 83%, 58%)', label: 'Purple' },
  { name: 'red', color: 'hsl(0, 84%, 60%)', label: 'Red' },
  { name: 'orange', color: 'hsl(25, 95%, 53%)', label: 'Orange' },
];

// Helper to convert HSL string to hex
const hslToHex = (hslString) => {
  if (!hslString) return '#000000';
  const match = hslString.match(/(\d+)\s+(\d+)%\s+(\d+)%/);
  if (!match) return '#000000';
  const h = parseInt(match[1]) / 360;
  const s = parseInt(match[2]) / 100;
  const l = parseInt(match[3]) / 100;
  
  const c = (1 - Math.abs(2 * l - 1)) * s;
  const x = c * (1 - Math.abs((h * 6) % 2 - 1));
  const m = l - c / 2;
  
  let r, g, b;
  if (h < 1/6) { r = c; g = x; b = 0; }
  else if (h < 2/6) { r = x; g = c; b = 0; }
  else if (h < 3/6) { r = 0; g = c; b = x; }
  else if (h < 4/6) { r = 0; g = x; b = c; }
  else if (h < 5/6) { r = x; g = 0; b = c; }
  else { r = c; g = 0; b = x; }
  
  r = Math.round((r + m) * 255);
  g = Math.round((g + m) * 255);
  b = Math.round((b + m) * 255);
  
  return `#${[r, g, b].map(x => x.toString(16).padStart(2, '0')).join('')}`;
};

// Helper to get current theme color as HSL string
const getCurrentThemeColorHsl = (colorScheme, theme, customThemeColor = null) => {
  // If custom color exists, return it
  if (colorScheme === 'custom' && customThemeColor) {
    return customThemeColor;
  }
  
  const colorMap = {
    green: { light: { h: 142, s: 76, l: 36 }, dark: { h: 142, s: 69, l: 58 } },
    blue: { light: { h: 217, s: 91, l: 60 }, dark: { h: 217, s: 85, l: 65 } },
    purple: { light: { h: 262, s: 83, l: 58 }, dark: { h: 262, s: 75, l: 65 } },
    red: { light: { h: 0, s: 84, l: 60 }, dark: { h: 0, s: 75, l: 65 } },
    orange: { light: { h: 25, s: 95, l: 53 }, dark: { h: 25, s: 90, l: 60 } },
  };
  
  const colors = colorMap[colorScheme] || colorMap.green;
  const activeColors = theme === 'dark' ? colors.dark : colors.light;
  return `${activeColors.h} ${activeColors.s}% ${activeColors.l}%`;
};

export default function Sidebar() {
  const { sidebarOpen, toggleSidebar, sidebarCollapsed, toggleSidebarCollapsed, initTheme, locale, colorScheme, setColorScheme, setCustomThemeColor, theme, customThemeColor } = useUIStore();
  const { logout, user } = useAuthStore();
  const menuItems = getMenuItems(locale);
  const [showColorPalette, setShowColorPalette] = useState(false);
  const [showColorPicker, setShowColorPicker] = useState(false);
  const [isLargeScreen, setIsLargeScreen] = useState(false);

  // Initialize theme on mount
  useEffect(() => {
    initTheme();
  }, [initTheme]);

  // Prevent collapse on small screens and check screen size
  useEffect(() => {
    const checkScreenSize = () => {
      const large = window.innerWidth >= 1024; // lg breakpoint
      setIsLargeScreen(large);
      // If on small screen and collapsed, expand it
      if (!large && sidebarCollapsed) {
        toggleSidebarCollapsed();
      }
    };
    
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
    return () => window.removeEventListener('resize', checkScreenSize);
  }, [sidebarCollapsed, toggleSidebarCollapsed]);

  // Close mobile sidebar when window is resized to desktop
  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth >= 1024 && !sidebarOpen) {
        // Auto-open on desktop if it was closed
      }
    };
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, [sidebarOpen]);

  // Close color palette when clicking outside
  useEffect(() => {
    if (!showColorPalette) return;
    
    const handleClickOutside = (event) => {
      const target = event.target;
      if (!target.closest('[data-color-palette]')) {
        setShowColorPalette(false);
      }
    };
    
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, [showColorPalette]);

  const handleLogout = async () => {
    try {
      await api.post('/auth/logout');
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      logout();
    }
  };

  return (
    <>
      {/* Mobile overlay */}
      {sidebarOpen && (
        <div
          className="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity"
          onClick={toggleSidebar}
        />
      )}

      {/* Sidebar */}
      <aside
        className={cn(
          'fixed left-0 top-0 z-50 h-full bg-background border-r border-border/50 transition-all duration-300 ease-in-out',
          sidebarOpen ? 'translate-x-0' : '-translate-x-full',
          'lg:translate-x-0',
          // Prevent collapse on small screens - always full width on mobile
          'w-64 lg:w-64',
          sidebarCollapsed && 'lg:w-16'
        )}
      >
        <div className="flex h-full flex-col">
          {/* Header */}
          <div className={cn(
            'flex h-14 items-center justify-between border-b border-border/50 shrink-0',
            SIDEBAR_STYLES.padding.header
          )}>
            {sidebarCollapsed && isLargeScreen ? (
              <>
                <div className="flex-1 flex justify-center">
                  <button
                    onClick={toggleSidebarCollapsed}
                    className={cn(
                      'h-9 w-9 bg-primary/30 dark:bg-primary/20 border border-primary/60 dark:border-primary/40 flex items-center justify-center hover:bg-primary/40 dark:hover:bg-primary/30 transition-colors cursor-pointer',
                      SIDEBAR_STYLES.rounded.logo
                    )}
                    title="Expand sidebar"
                  >
                    <PiCaretRight className="h-3.5 w-3.5 text-white fill-white" />
                  </button>
                </div>
              </>
            ) : (
              <>
                <div className="flex items-center gap-3 flex-1">
                  <div className={cn(
                    'h-9 w-9 bg-primary/10 border border-primary/20 flex items-center justify-center',
                    SIDEBAR_STYLES.rounded.logo
                  )}>
                    <span className="text-primary font-semibold text-base">P</span>
                  </div>
                  <h2 className="text-base font-bold text-muted-foreground tracking-tight">
                    PSF Dashboards
                  </h2>
                </div>
                <Button
                  variant="ghost"
                  size="icon"
                  onClick={() => {
                    // Only allow collapse on large screens
                    if (window.innerWidth >= 1024) {
                      toggleSidebarCollapsed();
                    }
                  }}
                  className="hidden lg:flex h-7 w-7 hover:bg-accent/50"
                  title="Collapse sidebar"
                >
                  <PiCaretRight className="h-3.5 w-3.5 rotate-180" />
                </Button>
                <Button
                  variant="ghost"
                  size="icon"
                  onClick={toggleSidebar}
                  className="lg:hidden h-7 w-7 hover:bg-accent/50"
                >
                  <PiX className="h-3.5 w-3.5" />
                </Button>
              </>
            )}
          </div>

          {/* Navigation */}
          <ScrollArea className="flex-1">
            <nav className={cn('space-y-0.5', SIDEBAR_STYLES.padding.container)}>
              {menuItems.map((item, idx) => (
                <MenuItem key={idx} item={item} collapsed={sidebarCollapsed && isLargeScreen} />
              ))}
            </nav>
          </ScrollArea>

          {/* Footer */}
          <div className={cn(
            'border-t border-border/50 space-y-2 shrink-0',
            SIDEBAR_STYLES.padding.footer
          )}>
            {/* Color Palette Selector */}
            {(!sidebarCollapsed || !isLargeScreen) && (
              <div className="relative" data-color-palette>
                <button
                  onClick={() => setShowColorPalette(!showColorPalette)}
                  className={cn(
                    'w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    'hover:bg-accent/50 text-muted-foreground'
                  )}
                >
                  <span>{t(locale, 'admin.settings.colorTheme')}</span>
                  <div className="flex items-center gap-2">
                    <div 
                      className="h-4 w-4 rounded-full border border-border/50"
                      style={{ backgroundColor: COLOR_PALETTES.find(p => p.name === colorScheme)?.color || COLOR_PALETTES[0].color }}
                    />
                  </div>
                </button>
                
                {showColorPalette && (
                  <div className="absolute bottom-full left-0 mb-2 w-full bg-card border border-border/50 rounded-lg shadow-xl p-2 z-50 animate-in fade-in-0 slide-in-from-bottom-2 duration-200">
                    <div className="grid grid-cols-5 gap-2 mb-2">
                      {COLOR_PALETTES.map((palette) => (
                        <button
                          key={palette.name}
                          onClick={() => {
                            setColorScheme(palette.name);
                            setShowColorPalette(false);
                          }}
                          className={cn(
                            'h-8 w-full rounded-md border-2 transition-all duration-200',
                            colorScheme === palette.name
                              ? 'border-primary ring-2 ring-primary/20 scale-110'
                              : 'border-border/50 hover:border-primary/50 hover:scale-105'
                          )}
                          style={{ backgroundColor: palette.color }}
                          title={palette.label}
                        />
                      ))}
                    </div>
                    <Button
                      variant="outline"
                      size="sm"
                      className="w-full gap-2"
                      onClick={() => {
                        setShowColorPalette(false);
                        setShowColorPicker(true);
                      }}
                    >
                      <FaPalette className="h-3.5 w-3.5" />
                      {t(locale, 'admin.settings.customColor')}
                    </Button>
                  </div>
                )}
              </div>
            )}

            {/* Collapsed Color Theme Button */}
            {sidebarCollapsed && isLargeScreen && (
              <button
                onClick={() => setShowColorPicker(true)}
                className={cn(
                  'w-full flex items-center justify-center transition-all duration-200',
                  SIDEBAR_STYLES.padding.menuItemCollapsed,
                  SIDEBAR_STYLES.rounded.menuItem,
                  'hover:bg-accent/50 text-muted-foreground'
                )}
                title={t(locale, 'admin.settings.colorTheme')}
              >
                <FaPalette className="h-5 w-5" />
              </button>
            )}

            {(!sidebarCollapsed || !isLargeScreen) && user && (
              <div className={cn(
                'bg-muted/30 border border-border/30 text-sm',
                SIDEBAR_STYLES.padding.userCard,
                SIDEBAR_STYLES.rounded.userCard
              )}>
                <div className="font-semibold truncate text-muted-foreground">{user.name || 'User'}</div>
                <div className="text-xs text-muted-foreground/70 truncate mt-0.5">{user.email}</div>
              </div>
            )}
          
            <Button
              variant="ghost"
              className={cn(
                'w-full transition-all duration-200 hover:bg-destructive/10 hover:text-destructive text-muted-foreground',
                (sidebarCollapsed && isLargeScreen) ? 'justify-center px-0' : 'justify-start gap-3'
              )}
              onClick={handleLogout}
              title={(sidebarCollapsed && isLargeScreen) ? t(locale, 'admin.common.logout') : undefined}
            >
              <PiSignOut className="h-4 w-4" />
              {(!sidebarCollapsed || !isLargeScreen) && <span className="text-sm">{t(locale, 'admin.common.logout')}</span>}
            </Button>
          </div>
        </div>
      </aside>

      {/* Color Picker Dialog */}
      <Dialog open={showColorPicker} onOpenChange={setShowColorPicker}>
        <DialogContent className="sm:max-w-[500px]">
          <DialogHeader>
            <DialogTitle>{t(locale, 'admin.settings.colorTheme')}</DialogTitle>
            <DialogDescription>
              {t(locale, 'admin.settings.selectColor')}
            </DialogDescription>
          </DialogHeader>
          <div className="py-4">
            <ColorPicker
              value={getCurrentThemeColorHsl(colorScheme, theme, customThemeColor)}
              onChange={(hslValue) => {
                // Save custom color to store (this will persist and apply it)
                setCustomThemeColor(hslValue);
              }}
            />
          </div>
          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => {
                // Reset to default green
                setColorScheme('green');
                setShowColorPicker(false);
              }}
            >
              {t(locale, 'admin.settings.resetToDefault')}
            </Button>
            <Button onClick={() => setShowColorPicker(false)}>
              {t(locale, 'admin.common.close')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
}
