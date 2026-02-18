import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuthStore } from '../lib/store';
import { useUIStore } from '../lib/stores/uiStore';
import api from '../lib/api';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Alert, AlertDescription } from '../components/ui/alert';
import { FaEye, FaEyeSlash, FaUser, FaLock, FaSpinner, FaMoon, FaSun, FaArrowRight } from 'react-icons/fa';
import LanguageToggle from '../components/LanguageToggle';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [logoUrl, setLogoUrl] = useState(null);
  const navigate = useNavigate();
  const { setUser, setToken, setPermissions, setRoles } = useAuthStore();
  const { locale, theme, toggleTheme, initTheme, setLocale } = useUIStore();

  // Initialize theme on mount
  useEffect(() => {
    initTheme();
  }, [initTheme]);

  // Load logo on mount and refresh periodically
  useEffect(() => {
    const loadLogo = async () => {
      try {
        // Use public settings endpoint (no auth required)
        // Add timestamp to prevent caching
        const response = await api.get('/settings/logo', {
          params: { _t: Date.now() }
        });
        if (response.data.logoUrl) {
          setLogoUrl(response.data.logoUrl + '?t=' + Date.now());
        }
      } catch (error) {
        // If logo endpoint fails or no logo set, use default
        console.error('Failed to load logo:', error);
      }
    };
    
    loadLogo();
    
    // Listen for logo updates from settings page
    const handleLogoUpdate = (event) => {
      if (event.detail?.logoUrl) {
        setLogoUrl(event.detail.logoUrl);
      }
    };
    window.addEventListener('logoUpdated', handleLogoUpdate);
    
    // Refresh logo every 30 seconds to pick up changes
    const interval = setInterval(loadLogo, 30000);
    
    return () => {
      clearInterval(interval);
      window.removeEventListener('logoUpdated', handleLogoUpdate);
    };
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const response = await api.post('/auth/login', { email, password });
      
      setToken(response.data.token);
      setUser(response.data.user);
      
      // Set permissions and roles if provided
      if (response.data.permissions) {
        setPermissions(response.data.permissions);
      }
      if (response.data.roles) {
        setRoles(response.data.roles);
      }
      
      navigate('/patients');
    } catch (err) {
      setError(err.response?.data?.error || 'Login failed. Please check your credentials.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className={`min-h-screen flex bg-background relative overflow-hidden ${locale === 'kh' ? 'font-khmer' : ''}`} lang={locale}>
      {/* Theme Toggle Button - Top Right */}
      <div className="absolute top-6 right-6 z-50 pointer-events-auto">
        <Button
          variant="ghost"
          size="icon"
          onClick={toggleTheme}
          className="h-9 w-9 rounded-full border border-border hover:bg-muted pointer-events-auto bg-background/80 backdrop-blur-sm"
          title={theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode'}
        >
          {theme === 'light' ? (
            <FaMoon className="h-4 w-4" />
          ) : (
            <FaSun className="h-4 w-4" />
          )}
        </Button>
      </div>

      {/* Left Section - Login Form */}
      <div className="flex-1 flex items-center justify-center p-8 lg:p-12 xl:p-16 relative z-10">
        {/* Language Toggle - Top Left */}
        <div className="absolute top-6 left-6 z-50 pointer-events-auto">
          <div className="bg-background/80 backdrop-blur-sm p-1 rounded-full ">
            <LanguageToggle />
          </div>
        </div>
        
        <div className="w-full max-w-md space-y-8 animate-fade-in">
          {/* Header Section with Logo */}
          <div className="text-center space-y-6">
            {/* Logo */}
            <div className="flex items-center justify-center">
              <div className="relative">
                <div className="h-32 w-32 rounded-full border-4 border-primary/20 bg-white p-2 shadow-lg flex items-center justify-center overflow-hidden">
                  <img 
                    key={logoUrl || 'default'} 
                    src={logoUrl || "https://tse2.mm.bing.net/th/id/OIP.m5Zpyrf5a2AjwderAqj26QHaHa?rs=1&pid=ImgDetMain&o=7&rm=3"} 
                    alt="Logo" 
                    className="h-full w-full object-contain rounded-full"
                    onError={(e) => {
                      // Fallback to default if custom logo fails to load
                      if (logoUrl) {
                        e.target.src = "https://tse2.mm.bing.net/th/id/OIP.m5Zpyrf5a2AjwderAqj26QHaHa?rs=1&pid=ImgDetMain&o=7&rm=3";
                      }
                    }}
                  />
                </div>
              </div>
            </div>
            
            {/* Title */}
            <div className="space-y-3">
              <h1 className="text-2xl lg:text-3xl font-bold tracking-tight">
                {locale === 'kh' 
                  ? 'ប្រព័ន្ធសម្រាប់គ្រប់គ្រងទិន្នន័យអ្នកជំងឺ (PSF)'
                  : 'Patient Dashboard System (PSF)'              
                }
              </h1>
              <p className="text-sm text-muted-foreground">
                {locale === 'kh' 
                  ? 'សូមចូលប្រើប្រាស់គណនីរបស់អ្នក'
                  : 'Please sign in to your account'
                }
              </p>
            </div>
          </div>

          {/* Login Form */}
          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Email/Username Field */}
            <div className="space-y-2">
              <Label htmlFor="email" className="text-sm font-semibold">
                {locale === 'kh' ? 'ឈ្មោះអ្នកប្រើប្រាស់' : 'Email'}
              </Label>
              <div className="relative">
                <FaUser className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground z-10" />
                <Input
                  id="email"
                  type="email"
                  placeholder={locale === 'kh' ? 'ឈ្មោះអ្នកប្រើប្រាស់' : 'name@example.com'}
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className={`pl-10 h-12 border-2 focus:border-primary transition-colors ${locale === 'kh' ? 'font-khmer' : ''}`}
                  required
                  disabled={loading}
                  autoComplete="email"
                />
              </div>
            </div>

            {/* Password Field */}
            <div className="space-y-2">
              <Label htmlFor="password" className="text-sm font-semibold">
                {locale === 'kh' ? 'ពាក្យសម្ងាត់' : 'Password'}
              </Label>
              <div className="relative">
                <FaLock className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground z-10" />
                <Input
                  id="password"
                  type={showPassword ? 'text' : 'password'}
                  placeholder={locale === 'kh' ? 'ពាក្យសម្ងាត់' : 'Enter your password'}
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className={`pl-10 pr-10 h-12 border-2 focus:border-primary transition-colors ${locale === 'kh' ? 'font-khmer' : ''}`}
                  required
                  disabled={loading}
                  autoComplete="current-password"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors z-10"
                  disabled={loading}
                >
                  {showPassword ? (
                    <FaEyeSlash className="h-4 w-4" />
                  ) : (
                    <FaEye className="h-4 w-4" />
                  )}
                </button>
              </div>
            </div>

            {/* Error Alert */}
            {error && (
              <Alert variant="destructive" className="animate-fade-in border-2">
                <AlertDescription className="text-sm font-medium">{error}</AlertDescription>
              </Alert>
            )}

            {/* Login Button */}
            <Button
              type="submit"
              className={`w-full h-12 text-base font-semibold bg-primary text-primary-foreground hover:bg-primary/90 shadow-lg hover:shadow-xl transition-all duration-200 ${locale === 'kh' ? 'font-khmer' : ''}`}
              disabled={loading}
            >
              {loading ? (
                <>
                  <FaSpinner className="h-4 w-4 mr-2 animate-spin" />
                  {locale === 'kh' ? 'កំពុងចូល...' : 'Signing in...'}
                </>
              ) : (
                <span className="flex items-center justify-center gap-2">
                  {locale === 'kh' ? 'ចូលប្រើប្រាស់' : 'Login'}
                  <FaArrowRight className="h-4 w-4" />
                </span>
              )}
            </Button>
          </form>
        </div>
      </div>

      {/* Right Section - Branding/Illustration */}
      <div className="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-primary via-primary/95 to-primary/90 relative overflow-hidden">
        {/* Background Pattern */}
        <div className="absolute inset-0 opacity-10">
          <div className="absolute inset-0" style={{
            backgroundImage: `radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0)`,
            backgroundSize: '40px 40px'
          }}></div>
        </div>

        {/* Gradient Overlay */}
        <div className="absolute inset-0 bg-gradient-to-br from-primary/50 via-primary/30 to-transparent"></div>
        
        {/* Content */}
        <div className={`relative z-10 flex flex-col items-center justify-center w-full h-full p-12 text-white ${locale === 'kh' ? 'font-khmer' : ''}`}>
          <div className="max-w-md space-y-8 text-center">
            {/* Branding */}
            <div className="space-y-6">
              <div className="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30">
                <span className="text-3xl font-bold">PSF</span>
              </div>
              
              <div className="space-y-3">
                <h2 className="text-4xl font-bold tracking-tight">
                  {locale === 'kh' ? 'ប្រព័ន្ធរបាយការណ៍' : 'Reporting System'}
                </h2>
                <p className="text-lg text-white/90 leading-relaxed">
                  {locale === 'kh' 
                    ? 'គ្រប់គ្រង និងវិភាគទិន្នន័យអ្នកជំងឺដោយមានប្រសិទ្ធភាព'
                    : 'Efficiently manage and analyze patient data'
                  }
                </p>
              </div>
            </div>

            {/* Features List */}
            <div className="space-y-4 pt-8 border-t border-white/20">
              <div className="flex items-start gap-3 text-left">
                <div className="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <div className="w-2 h-2 rounded-full bg-white"></div>
                </div>
                <p className="text-sm text-white/90">
                  {locale === 'kh' 
                    ? 'ការគ្រប់គ្រងទិន្នន័យដែលមានសុវត្ថិភាព'
                    : 'Secure data management'
                  }
                </p>
              </div>
              <div className="flex items-start gap-3 text-left">
                <div className="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <div className="w-2 h-2 rounded-full bg-white"></div>
                </div>
                <p className="text-sm text-white/90">
                  {locale === 'kh' 
                    ? 'របាយការណ៍ពេលវេលាពិត'
                    : 'Real-time reporting'
                  }
                </p>
              </div>
              <div className="flex items-start gap-3 text-left">
                <div className="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <div className="w-2 h-2 rounded-full bg-white"></div>
                </div>
                <p className="text-sm text-white/90">
                  {locale === 'kh' 
                    ? 'វិភាគទិន្នន័យដែលមានប្រសិទ្ធភាព'
                    : 'Efficient data analytics'
                  }
                </p>
              </div>
            </div>
          </div>
        </div>

        {/* Decorative Elements */}
        <div className="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div className="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
        <div className="absolute top-1/2 left-1/2 w-64 h-64 bg-white/5 rounded-full blur-2xl -translate-x-1/2 -translate-y-1/2"></div>
      </div>
    </div>
  );
}
