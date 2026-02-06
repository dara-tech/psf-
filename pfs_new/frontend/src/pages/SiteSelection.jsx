import { useState, useEffect } from 'react';
import { useParams, useNavigate, useLocation } from 'react-router-dom';
import api from '../lib/api';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Alert, AlertDescription } from '../components/ui/alert';
import { Skeleton } from '../components/ui/skeleton';
import { FaSpinner, FaMapMarkerAlt, FaHospital, FaMoon, FaSun } from 'react-icons/fa';
import { useUIStore } from '../lib/stores/uiStore';

export default function SiteSelection() {
  const { locale: urlLocale } = useParams();
  const navigate = useNavigate();
  const location = useLocation();
  
  // Detect parent type from URL pathname
  const parent = location.pathname.startsWith('/provider') ? 'provider' : 'client';
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [allSites, setAllSites] = useState([]);
  const [tokens, setTokens] = useState([]);
  const [selectedProvince, setSelectedProvince] = useState('');
  const [selectedSite, setSelectedSite] = useState('');
  const [locale, setLocale] = useState(urlLocale || 'kh');
  const { theme, toggleTheme, initTheme } = useUIStore();

  // Initialize theme on mount
  useEffect(() => {
    initTheme();
  }, [initTheme]);

  useEffect(() => {
    loadSitesAndTokens();
  }, [parent, locale]);

  const loadSitesAndTokens = async () => {
    try {
      setLoading(true);
      setError('');
      
      // Try to fetch from Laravel route first (old system) - this works with the Laravel backend
      try {
        const response = await fetch(`/${parent}/index/${locale || 'kh'}`);
        if (response.ok) {
          const html = await response.text();
          
          // Parse the HTML to extract the JavaScript variables
          const sitesMatch = html.match(/var allSites\s*=\s*(\[.*?\])\s*;/s);
          const tokensMatch = html.match(/var tokens\s*=\s*(\[.*?\])\s*;/s);
          const localeMatch = html.match(/var locale\s*=\s*"([^"]+)"/);
          
          if (sitesMatch) {
            try {
              const sites = JSON.parse(sitesMatch[1]);
              // Transform to match expected format (Laravel uses 'site' for Khmer, 'sitename' for English)
              const transformedSites = sites.map(site => ({
                username: site.username,
                site_kh: site.site_kh || site.site || '',
                site_en: site.site_en || site.sitename || '',
                province: site.province || '',
                province_kh: site.province_kh || site.province || ''
              }));
              setAllSites(transformedSites);
            } catch (e) {
              console.error('Error parsing sites:', e);
            }
          }
          
          if (tokensMatch) {
            try {
              const tokensData = JSON.parse(tokensMatch[1]);
              setTokens(tokensData);
            } catch (e) {
              console.error('Error parsing tokens:', e);
            }
          }
          
          if (localeMatch) {
            setLocale(localeMatch[1]);
          }
          
          if (sitesMatch && tokensMatch) {
            return; // Successfully loaded from Laravel
          }
        }
      } catch (fetchError) {
        console.log('Laravel route not available, trying API...', fetchError);
      }
      
      // Fallback: Try API endpoints if available
      try {
        const [sitesResponse, tokensResponse] = await Promise.all([
          api.get('/questionnaire/sites').catch(() => null),
          api.get('/questionnaire/tokens').catch(() => null)
        ]);
        
        if (sitesResponse?.data) {
          const sites = sitesResponse.data.sites || sitesResponse.data || [];
          const transformedSites = sites.map(site => ({
            username: site.username,
            site_kh: site.site || site.site_kh,
            site_en: site.sitename || site.site_en,
            province: site.province || '',
            province_kh: site.province_kh || site.province || ''
          }));
          setAllSites(transformedSites);
        }
        if (tokensResponse?.data) {
          setTokens(tokensResponse.data.tokens || tokensResponse.data || []);
        }
      } catch (apiError) {
        console.error('API fetch failed:', apiError);
        setError(locale === 'en' ? 'Failed to load sites. Please try again.' : 'មិនអាចផ្ទុកតំបន់បានទេ។ សូមព្យាយាមម្តងទៀត។');
      }
    } catch (error) {
      console.error('Error loading sites:', error);
      setError(locale === 'en' ? 'Failed to load sites. Please try again.' : 'មិនអាចផ្ទុកតំបន់បានទេ។ សូមព្យាយាមម្តងទៀត។');
    } finally {
      setLoading(false);
    }
  };

  // Get unique provinces
  const getProvinces = () => {
    const provinces = new Map();
    allSites.forEach(site => {
      if (site.province && site.province !== '*') {
        if (!provinces.has(site.province)) {
          provinces.set(site.province, {
            province: site.province,
            province_kh: site.province_kh || site.province,
            sites: []
          });
        }
        provinces.get(site.province).sites.push(site);
      }
    });
    return Array.from(provinces.values()).sort((a, b) => 
      (locale === 'kh' ? a.province_kh : a.province).localeCompare(locale === 'kh' ? b.province_kh : b.province)
    );
  };

  // Get sites for selected province
  const getSitesForProvince = () => {
    if (!selectedProvince) return [];
    // Match province by comparing both with and without spaces (like old system)
    return allSites.filter(site => {
      const siteProvince = (site.province || '').replace(/\s/g, '');
      const selectedProvinceClean = selectedProvince.replace(/\s/g, '');
      return site.province === selectedProvince || siteProvince === selectedProvinceClean;
    });
  };

  const handleProvinceSelect = (province) => {
    setSelectedProvince(province);
    setSelectedSite(''); // Reset site selection when province changes
  };

  const handleSiteSelect = (siteUsername) => {
    setSelectedSite(siteUsername);
  };

  const handleNext = () => {
    if (!selectedSite) {
      setError(locale === 'en' ? 'Please select a site' : 'សូមជ្រើសរើសតំបន់');
      return;
    }

    // Find token that matches the selected site username
    const token = tokens.find(t => t.username === selectedSite);
    if (token) {
      navigate(`/${parent}/${token.code}/${locale}`);
    } else {
      setError(locale === 'en' ? 'No token found for selected site' : 'រកមិនឃើញ token សម្រាប់តំបន់ដែលបានជ្រើស');
    }
  };

  const provinces = getProvinces();
  const availableSites = getSitesForProvince();

  if (loading) {
    return (
      <div className={`min-h-screen bg-background ${locale === 'kh' ? 'font-khmer' : ''} relative`} lang={locale}>
        {/* Theme Toggle Button - Top Right */}
        <div className="fixed top-4 right-4 z-50">
          <Button
            variant="ghost"
            size="icon"
            onClick={toggleTheme}
            className="h-10 w-10 rounded-full bg-background/95 backdrop-blur-sm border border-border hover:bg-accent"
            title={theme === 'light' ? (locale === 'en' ? 'Switch to dark mode' : 'ប្តូរទៅរបៀបងងឹត') : (locale === 'en' ? 'Switch to light mode' : 'ប្តូរទៅរបៀបពន្លឺ')}
          >
            {theme === 'light' ? (
              <FaMoon className="h-5 w-5" />
            ) : (
              <FaSun className="h-5 w-5" />
            )}
          </Button>
        </div>
        
        <div className="w-full max-w-2xl mx-auto pt-16 pb-6">
          <div className="mb-6 px-4">
            <Skeleton className="h-8 w-3/4" />
          </div>
          <div className="space-y-4 px-4">
            <Skeleton className="h-12 w-full rounded-lg" />
            <Skeleton className="h-12 w-full rounded-lg" />
            <Skeleton className="h-12 w-full rounded-lg" />
            <Skeleton className="h-12 w-full rounded-lg" />
          </div>
        </div>
      </div>
    );
  }

  if (error && !allSites.length) {
    return (
      <div className={`min-h-screen bg-background flex items-center justify-center ${locale === 'kh' ? 'font-khmer' : ''}`} lang={locale}>
        <div className="w-full max-w-2xl mx-4">
          <Alert variant="destructive">
            <AlertDescription>{error}</AlertDescription>
          </Alert>
        </div>
      </div>
    );
  }

  return (
    <div className={`min-h-screen bg-background ${locale === 'kh' ? 'font-khmer' : ''} relative`} lang={locale}>
      {/* Theme Toggle Button - Top Right */}
      <div className="fixed top-4 right-4 z-50">
        <Button
          variant="ghost"
          size="icon"
          onClick={toggleTheme}
          className="h-10 w-10 rounded-full bg-background/95 backdrop-blur-sm border border-border hover:bg-accent"
          title={theme === 'light' ? (locale === 'en' ? 'Switch to dark mode' : 'ប្តូរទៅរបៀបងងឹត') : (locale === 'en' ? 'Switch to light mode' : 'ប្តូរទៅរបៀបពន្លឺ')}
        >
          {theme === 'light' ? (
            <FaMoon className="h-5 w-5" />
          ) : (
            <FaSun className="h-5 w-5" />
          )}
        </Button>
      </div>

      <div className="w-full max-w-2xl mx-auto pt-16 pb-6">
        {/* Title */}
        <div className="mb-6 px-4">
          <h1 className={`text-2xl font-bold text-primary ${locale === 'kh' ? 'font-khmer' : ''}`}>
            {locale === 'en' 
              ? (parent === 'client' ? 'Patient Satisfaction Feedback (PSF) Online Form' : 'Provider Questionnaire')
              : (parent === 'client' ? 'កម្រងសំណួរព័ត៌មានត្រឡប់សម្រាប់អ្នកជំងឺ​ (PSF)' : 'កម្រងសំណួរសម្រាប់អ្នកផ្តល់សេវា')}
          </h1>
        </div>

        {error && (
          <Alert variant="destructive" className="mb-6 mx-4">
            <AlertDescription>{error}</AlertDescription>
          </Alert>
        )}

        {/* Single Column Layout - mobile app style */}
        <div className="space-y-6 px-4">
          {/* Province Selection */}
          <div className="border-0 shadow-none bg-transparent">
            <h2 className={`text-xl font-bold text-primary mb-4 flex items-center gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
              <FaMapMarkerAlt className="text-primary" />
              {locale === 'en' ? 'Province' : 'ខេត្ត/ក្រុង'}
            </h2>
            <div className="space-y-2 mb-6">
              {provinces.map((province, idx) => {
                const isSelected = selectedProvince === province.province;
                return (
                  <label
                    key={idx}
                    className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                      isSelected
                        ? 'border-primary bg-primary/10'
                        : 'border-border hover:bg-accent/50'
                    }`}
                  >
                    <input
                      type="radio"
                      name="province"
                      value={province.province}
                      checked={isSelected}
                      onChange={() => handleProvinceSelect(province.province)}
                      className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary flex-shrink-0"
                    />
                    <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-semibold text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                      {locale === 'kh' ? (province.province_kh || province.province) : province.province}
                    </span>
                  </label>
                );
              })}
            </div>
          </div>

          {/* Site Selection */}
          {selectedProvince && (
            <div className="border-0 shadow-none bg-transparent">
              <h2 className={`text-xl font-bold text-primary mb-4 flex items-center gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                <FaHospital className="text-primary" />
                {locale === 'en' ? 'Site' : 'តំបន់'}
              </h2>
              {availableSites.length > 0 ? (
                <>
                  <div className="space-y-2 mb-6">
                    {availableSites.map((site, idx) => {
                      const isSelected = selectedSite === site.username;
                      return (
                        <label
                          key={idx}
                          className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                            isSelected
                              ? 'border-primary bg-primary/10'
                              : 'border-border hover:bg-accent/50'
                          }`}
                        >
                          <input
                            type="radio"
                            name="site"
                            value={site.username}
                            checked={isSelected}
                            onChange={() => handleSiteSelect(site.username)}
                            className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary flex-shrink-0"
                            required
                          />
                          <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-semibold text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                            {locale === 'kh' ? (site.site_kh || site.site || '') : (site.site_en || site.sitename || '')}
                          </span>
                        </label>
                      );
                    })}
                  </div>
                  <Button
                    onClick={handleNext}
                    disabled={!selectedSite}
                    className="w-full h-12 text-base font-semibold"
                    size="lg"
                  >
                    {locale === 'en' ? 'Next' : 'បន្ត'}
                  </Button>
                </>
              ) : (
                <p className={`text-base text-muted-foreground text-center py-8 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                  {locale === 'en' ? 'No sites available for this province' : 'គ្មានតំបន់សម្រាប់ខេត្តនេះ'}
                </p>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

