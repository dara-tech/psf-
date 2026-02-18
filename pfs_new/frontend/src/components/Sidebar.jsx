import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuthStore } from '../lib/store';
import { useUIStore } from '../lib/stores/uiStore';
import { t } from '../lib/translations/index';
import api from '../lib/api';
import {
  PiFilesFill,
  PiChartLineFill,
  PiUsersFill,
  PiShieldFill,
  PiBriefcaseFill,
  PiBuildingsFill,
  PiSignOut,
  PiCaretRight,
  PiGearFill,
  PiGraphFill,
  PiQrCode,
  PiQuestionFill,
} from 'react-icons/pi';
import { ChevronRight, ChevronLeft, PanelLeftClose, PanelLeft } from 'lucide-react';
import { Button } from './ui/button';
import {
  Sidebar as ShadcnSidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarMenuSub,
  SidebarMenuSubButton,
  SidebarMenuSubItem,
  SidebarRail,
  useSidebar,
} from './ui/sidebar';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from './ui/collapsible';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from './ui/dialog';
import { ColorPicker } from './ui/color-picker';
import { FaPalette } from 'react-icons/fa';
import { cn } from '../lib/utils';

// Consistent sidebar icon styling (theme color via sidebar-primary)
const SIDEBAR_ICON_CLASS = 'size-4 shrink-0 text-sidebar-primary';

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

// Color palette options
const COLOR_PALETTES = [
  { name: 'green', color: 'hsl(142, 76%, 36%)', label: 'Green' },
  { name: 'blue', color: 'hsl(217, 91%, 60%)', label: 'Blue' },
  { name: 'purple', color: 'hsl(262, 83%, 58%)', label: 'Purple' },
  { name: 'red', color: 'hsl(0, 84%, 60%)', label: 'Red' },
  { name: 'orange', color: 'hsl(25, 95%, 53%)', label: 'Orange' },
];

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

const getCurrentThemeColorHsl = (colorScheme, theme, customThemeColor = null) => {
  if (colorScheme === 'custom' && customThemeColor) return customThemeColor;
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

function NavMenu({ menuItems }) {
  const location = useLocation();
  const { hasPermission } = useAuthStore();

  const renderItem = (item, idx) => {
    if (item.permission && !hasPermission(item.permission)) return null;

    const hasChildren = item.children?.length > 0;
    const isActive = item.path && location.pathname === item.path;
    const hasActiveChild = hasChildren && item.children.some(ch => {
      if (ch.path) return location.pathname === ch.path;
      return ch.children?.some(gc => gc.path === location.pathname);
    });

    if (hasChildren) {
      return (
        <Collapsible
          key={idx}
          asChild
          defaultOpen={hasActiveChild}
          className="group/collapsible"
        >
          <SidebarMenuItem>
            <CollapsibleTrigger asChild>
              <SidebarMenuButton tooltip={item.title}>
                {item.icon && <item.icon className={SIDEBAR_ICON_CLASS} />}
                <span>{item.title}</span>
                <ChevronRight className={cn('ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90', SIDEBAR_ICON_CLASS)} />
              </SidebarMenuButton>
            </CollapsibleTrigger>
            <CollapsibleContent>
              <SidebarMenuSub>
                {item.children.map((child, cIdx) => {
                  if (child.permission && !hasPermission(child.permission)) return null;
                  if (child.children?.length) {
                    return (
                      <React.Fragment key={cIdx}>
                        <div className="px-2.5 py-1 text-xs font-semibold text-sidebar-foreground/70 group-data-[collapsible=icon]:hidden">
                          {child.title}
                        </div>
                        {child.children.map((grandchild, gIdx) => {
                          if (grandchild.permission && !hasPermission(grandchild.permission)) return null;
                          const active = grandchild.path === location.pathname;
                          return (
                            <SidebarMenuSubItem key={gIdx}>
                              <SidebarMenuSubButton asChild isActive={active}>
                                <Link to={grandchild.path}>
                                  {grandchild.icon && <grandchild.icon className={SIDEBAR_ICON_CLASS} />}
                                  <span>{grandchild.title}</span>
                                </Link>
                              </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                          );
                        })}
                      </React.Fragment>
                    );
                  }
                  const childActive = child.path === location.pathname;
                  return (
                    <SidebarMenuSubItem key={cIdx}>
                      <SidebarMenuSubButton asChild isActive={childActive}>
                        <Link to={child.path}>
                          {child.icon && <child.icon className={SIDEBAR_ICON_CLASS} />}
                          <span>{child.title}</span>
                        </Link>
                      </SidebarMenuSubButton>
                    </SidebarMenuSubItem>
                  );
                })}
              </SidebarMenuSub>
            </CollapsibleContent>
          </SidebarMenuItem>
        </Collapsible>
      );
    }

    return (
      <SidebarMenuItem key={idx}>
        <SidebarMenuButton asChild tooltip={item.title} isActive={isActive}>
          <Link to={item.path}>
            {item.icon && <item.icon className={SIDEBAR_ICON_CLASS} />}
            <span>{item.title}</span>
          </Link>
        </SidebarMenuButton>
      </SidebarMenuItem>
    );
  };

  return (
    <SidebarGroup>
      <SidebarGroupLabel className="group-data-[collapsible=icon]:hidden">
        Navigation
      </SidebarGroupLabel>
      <SidebarGroupContent>
        <SidebarMenu>
          {menuItems.map((item, idx) => renderItem(item, idx))}
        </SidebarMenu>
      </SidebarGroupContent>
    </SidebarGroup>
  );
}

export default function Sidebar() {
  const { initTheme, locale, colorScheme, setColorScheme, setCustomThemeColor, theme, customThemeColor } = useUIStore();
  const { isMobile, state, toggleSidebar } = useSidebar();
  const { logout, user } = useAuthStore();
  const menuItems = getMenuItems(locale);
  const [showColorPalette, setShowColorPalette] = useState(false);
  const [showColorPicker, setShowColorPicker] = useState(false);

  useEffect(() => {
    initTheme();
  }, [initTheme]);

  useEffect(() => {
    if (!showColorPalette) return;
    const handleClickOutside = (event) => {
      if (!event.target.closest('[data-color-palette]')) setShowColorPalette(false);
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

  const collapsed = state === 'collapsed';
  const showLabels = !collapsed || !isMobile;

  return (
    <>
      <ShadcnSidebar collapsible="icon" variant="inset">
        <SidebarHeader className="relative border-b border-sidebar-border/80">
          <div className={cn(
            "flex h-14 items-center transition-[padding] duration-200",
            collapsed ? "justify-center px-0" : "justify-between gap-2 px-4"
          )}>
            {/* Logo + title: hidden when collapsed */}
            <div className={cn(
              "flex items-center min-w-0",
              collapsed ? "hidden" : "flex-1 gap-3"
            )}>
              <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sidebar-primary text-sidebar-primary-foreground shadow-sm ring-1 ring-sidebar-primary/20 transition-shadow duration-200">
                <span className="font-semibold text-base">P</span>
              </div>
              <span className="font-bold text-sidebar-foreground truncate tracking-tight">
                PSF Dashboards
              </span>
            </div>
            {/* Expand/collapse button: icon only */}
            {!isMobile && (
              <Button
                variant="ghost"
                size="icon"
                onClick={toggleSidebar}
                className="h-8 w-8 shrink-0 rounded-lg text-sidebar-foreground transition-colors duration-200 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                title={collapsed ? "Expand sidebar" : "Collapse sidebar"}
              >
                {collapsed ? <PanelLeft className={SIDEBAR_ICON_CLASS} /> : <PanelLeftClose className={SIDEBAR_ICON_CLASS} />}
              </Button>
            )}
          </div>
        </SidebarHeader>

        <SidebarContent>
          <NavMenu menuItems={menuItems} />
        </SidebarContent>

        <SidebarFooter className="border-t border-sidebar-border/80">
          {collapsed ? (
            /* Collapsed: expand button first, then color + logout */
            <SidebarMenu>
              <SidebarMenuItem>
                <SidebarMenuButton
                  tooltip={locale === 'kh' ? 'ពង្រីករបារចំហៀង' : 'Expand sidebar'}
                  onClick={toggleSidebar}
                  className="bg-sidebar-accent/50 hover:bg-sidebar-accent text-sidebar-primary"
                  title={locale === 'kh' ? 'ពង្រីករបារចំហៀង' : 'Expand sidebar'}
                >
                  <PanelLeft className={SIDEBAR_ICON_CLASS} />
                </SidebarMenuButton>
              </SidebarMenuItem>
              <SidebarMenuItem>
                <SidebarMenuButton
                  tooltip={t(locale, 'admin.settings.colorTheme')}
                  onClick={() => setShowColorPicker(true)}
                >
                  <FaPalette className={SIDEBAR_ICON_CLASS} />
                </SidebarMenuButton>
              </SidebarMenuItem>
              <SidebarMenuItem>
                <SidebarMenuButton
                  tooltip={t(locale, 'admin.common.logout')}
                  onClick={handleLogout}
                  className="hover:bg-destructive/10 hover:text-destructive"
                >
                  <PiSignOut className={SIDEBAR_ICON_CLASS} />
                </SidebarMenuButton>
              </SidebarMenuItem>
            </SidebarMenu>
          ) : (
            /* Expanded: full labels and content */
            <>
              <div className="relative" data-color-palette>
                <button
                  type="button"
                  onClick={() => setShowColorPalette(!showColorPalette)}
                  className="flex w-full items-center justify-between rounded-lg p-2.5 text-sm font-medium text-sidebar-foreground transition-colors duration-200 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                >
                  <span>{t(locale, 'admin.settings.colorTheme')}</span>
                  <div
                    className="h-4 w-4 rounded-full border border-sidebar-border"
                    style={{ backgroundColor: COLOR_PALETTES.find(p => p.name === colorScheme)?.color || COLOR_PALETTES[0].color }}
                  />
                </button>
                {showColorPalette && (
                  <div className="absolute bottom-full left-0 mb-2 w-full rounded-xl border border-sidebar-border/80 bg-sidebar/95 backdrop-blur-xl p-2.5 shadow-xl animate-in fade-in-0 slide-in-from-bottom-2 z-50">
                    <div className="grid grid-cols-5 gap-2 mb-2">
                      {COLOR_PALETTES.map((palette) => (
                        <button
                          key={palette.name}
                          type="button"
                          onClick={() => {
                            setColorScheme(palette.name);
                            setShowColorPalette(false);
                          }}
                          className={cn(
                            'h-8 w-full rounded-md border-2 transition-all',
                            colorScheme === palette.name
                              ? 'border-sidebar-primary ring-2 ring-sidebar-primary/20 scale-110'
                              : 'border-sidebar-border hover:border-sidebar-primary/50 hover:scale-105'
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
              {user && (
                <div className="rounded-xl border border-sidebar-border/60 bg-sidebar-accent/40 p-2.5 text-sm shadow-sm">
                  <div className="font-semibold truncate text-sidebar-foreground">{user.name || 'User'}</div>
                  <div className="text-xs text-sidebar-foreground/70 truncate mt-0.5">{user.email}</div>
                </div>
              )}
              <SidebarMenu>
                <SidebarMenuItem>
                  <SidebarMenuButton
                    onClick={handleLogout}
                    className="w-full rounded-lg hover:bg-destructive/10 hover:text-destructive text-sidebar-foreground transition-colors duration-200"
                  >
                    <PiSignOut className={SIDEBAR_ICON_CLASS} />
                    <span>{t(locale, 'admin.common.logout')}</span>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              </SidebarMenu>
            </>
          )}
        </SidebarFooter>

        <SidebarRail />
      </ShadcnSidebar>

      <Dialog open={showColorPicker} onOpenChange={setShowColorPicker}>
        <DialogContent className="sm:max-w-[500px]">
          <DialogHeader>
            <DialogTitle>{t(locale, 'admin.settings.colorTheme')}</DialogTitle>
            <DialogDescription>{t(locale, 'admin.settings.selectColor')}</DialogDescription>
          </DialogHeader>
          <div className="py-4">
            <ColorPicker
              value={getCurrentThemeColorHsl(colorScheme, theme, customThemeColor)}
              onChange={setCustomThemeColor}
            />
          </div>
          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => {
                setColorScheme('green');
                setShowColorPicker(false);
              }}
            >
              {t(locale, 'admin.settings.resetToDefault')}
            </Button>
            <Button onClick={() => setShowColorPicker(false)}>{t(locale, 'admin.common.close')}</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
}
