import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import api from '../lib/api';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Alert, AlertDescription } from '../components/ui/alert';
import { Skeleton } from '../components/ui/skeleton';
import { FaSpinner, FaMoon, FaSun, FaCheckCircle } from 'react-icons/fa';
import { useUIStore } from '../lib/stores/uiStore';
import TTSButton from '../components/TTSButton';
import { t } from '../lib/translations/index';

export default function ProviderQuestionnaire() {
  const { token, locale: urlLocale, uuid, index } = useParams();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [formData, setFormData] = useState({});
  const [pageData, setPageData] = useState(null);
  const [error, setError] = useState('');
  const { theme, toggleTheme, initTheme, locale: uiLocale } = useUIStore();
  const locale = urlLocale || uiLocale || 'kh';

  // Initialize theme on mount
  useEffect(() => {
    initTheme();
  }, [initTheme]);

  useEffect(() => {
    // If token is "index" or missing, redirect to site selection (like old system)
    if (!token || token === 'index') {
      navigate(`/provider/index/${locale}`, { replace: true });
      return;
    }
    
    // If we're on thank page, we still need to load page data for proper rendering
    loadPage();
  }, [token, locale, uuid, index, navigate]);

  // Set default consent to "Yes" when on consent page (only if not already set)
  useEffect(() => {
    if (pageData && (index === 'consent' || (!index && pageData.page === 'provider'))) {
      // Only set default if consent is truly undefined/null, not if it's '0' (disagree)
      if (formData.consent === undefined || formData.consent === null || formData.consent === '') {
        setFormData(prev => ({ ...prev, consent: '1' }));
      }
    }
  }, [index, pageData]);

  const loadPage = async () => {
    try {
      setLoading(true);
      setError('');
      const url = uuid && index 
        ? `/questionnaire/provider/${token}/${locale}/${uuid}/${index}`
        : `/questionnaire/provider/${token}/${locale}`;
      
      const response = await api.get(url);
      setPageData(response.data);
      
      // If we got a UUID but don't have one in URL, redirect
      if (response.data.uuid && !uuid && !index) {
        navigate(`/provider/${token}/${locale}/${response.data.uuid}/consent`, { replace: true });
        return;
      }
    } catch (error) {
      console.error('Error loading page:', error);
      setError(error.response?.data?.error || 'Failed to load page');
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    setError('');

    try {
      const submitData = { ...formData, locale: locale, _uri: uuid };
      console.log('[Provider Form] Submitting data:', {
        index,
        consent: formData.consent,
        consentType: typeof formData.consent,
        submitData
      });

      const response = await api.post(
        `/questionnaire/provider/${token}/${index}`,
        submitData
      );
      
      console.log('[Provider Form] Response:', response.data);
      
      if (response.data.redirect) {
        navigate(response.data.redirect);
      }
    } catch (error) {
      console.error('Error saving:', error);
      console.error('Error details:', {
        message: error.message,
        response: error.response?.data,
        status: error.response?.status
      });
      setError(error.response?.data?.error || error.response?.data?.details || 'Failed to save data');
    } finally {
      setSaving(false);
    }
  };

  if (index === 'thank') {
    return (
      <div className={`min-h-screen flex items-center justify-center bg-background ${locale === 'kh' ? 'font-khmer' : ''} relative`} lang={locale}>
        {/* Theme Toggle Button - Top Right (match client thank page) */}
        <div className="absolute top-4 right-4 z-10">
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
        <Card className="max-w-2xl w-full mx-4 text-center shadow-xl border border-primary/20 bg-card">
          <CardHeader className="pb-4">
            <div className="mx-auto mb-4 w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center">
              <FaCheckCircle className="text-5xl text-primary" />
            </div>
            <CardTitle className={`text-3xl font-bold mb-2 bg-gradient-to-r from-primary to-primary/70 bg-clip-text text-transparent ${locale === 'kh' ? 'font-khmer' : ''}`}>
              {locale === 'en' ? 'Thank You!' : 'សូមអរគុណ!'}
            </CardTitle>
            <CardDescription className={`text-lg ${locale === 'kh' ? 'font-khmer' : ''}`}>
              {locale === 'en' 
                ? 'Thank you for completing the questionnaire.' 
                : 'សូមអរគុណសម្រាប់ការបំពេញ។'}
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              <div className="p-3 sm:p-4 rounded-lg bg-primary/5 border border-primary/20">
                <p className={`text-sm text-muted-foreground flex items-center justify-center gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                  <FaCheckCircle className="text-primary" />
                  {locale === 'en'
                    ? 'Your responses have been saved successfully.'
                    : 'ការឆ្លើយតបរបស់អ្នកត្រូវបានរក្សាទុកដោយជោគជ័យ។'}
                </p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    );
  }

  if (error && !pageData && index !== 'thank') {
    return (
      <div className="container mx-auto px-3 sm:px-4 md:px-6 py-3 sm:py-6">
        <Alert variant="destructive">
          <AlertDescription>{error}</AlertDescription>
        </Alert>
      </div>
    );
  }

  if (!pageData && index !== 'thank') {
    return null;
  }

  // Render consent page - matching client consent page UI
  if (index === 'consent' || (!index && pageData && pageData.page === 'provider')) {
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
          {/* Title - matching client consent page */}
          <div className="mb-6 px-4">
            <h2 className={`text-2xl font-bold text-primary ${locale === 'kh' ? 'font-khmer' : ''}`}>
              {t(locale, 'provider.questions.title')} {pageData?.site ? `- ${pageData.site}` : ''}
            </h2>
          </div>

          <Card className="border-0 shadow-none bg-transparent">
            <CardContent className="p-0">
              {error && (
                <Alert variant="destructive" className="mb-6 mx-4">
                  <AlertDescription>{error}</AlertDescription>
                </Alert>
              )}
              
              {/* Section Title - matching client consent page */}
              <div className="mb-6 px-4">
                <h3 className={`text-xl font-bold text-primary ${locale === 'kh' ? 'font-khmer' : ''}`}>
                  {t(locale, 'provider.questions.section1')}
                </h3>
              </div>

              <form onSubmit={handleSubmit}>
                {/* Consent text - matching client consent page */}
                <div className="mb-6 px-4">
                  <p 
                    className={`text-base text-muted-foreground leading-relaxed ${locale === 'kh' ? 'font-khmer' : ''}`}
                    dangerouslySetInnerHTML={{ __html: t(locale, 'provider.questions.consent') }}
                  />
                </div>

                {/* Radio buttons - vertical layout like client consent page */}
                <div className="space-y-2 mb-8 px-4">
                  <label 
                    htmlFor="consent-yes"
                    className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors hover:bg-accent/50 ${
                      (formData.consent === '1' || formData.consent === 1) ? 'border-primary bg-primary/10' : 'border-border'
                    } ${locale === 'kh' ? 'font-khmer' : ''}`}
                  >
                    <input
                      type="radio"
                      id="consent-yes"
                      name="consent"
                      value="1"
                      checked={formData.consent === '1' || formData.consent === 1}
                      onChange={handleInputChange}
                      className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary"
                      required
                    />
                    <span className="text-base flex-1">{locale === 'en' ? 'Yes' : 'បាទ/ចាស'}</span>
                  </label>
                  <label 
                    htmlFor="consent-no"
                    className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors hover:bg-accent/50 ${
                      (formData.consent === '0' || formData.consent === 0) ? 'border-primary bg-primary/10' : 'border-border'
                    } ${locale === 'kh' ? 'font-khmer' : ''}`}
                  >
                    <input
                      type="radio"
                      id="consent-no"
                      name="consent"
                      value="0"
                      checked={formData.consent === '0' || formData.consent === 0}
                      onChange={handleInputChange}
                      className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary"
                      required
                    />
                    <span className="text-base flex-1">{locale === 'en' ? 'No' : 'ទេ'}</span>
                  </label>
                </div>

                {/* Next button - matching client consent page */}
                <div className="mt-6 px-4 pb-4">
                  <Button 
                    type="submit" 
                    disabled={saving || (formData.consent === undefined || formData.consent === null || formData.consent === '')} 
                    className="w-full h-12 text-base font-semibold"
                    size="lg"
                  >
                    {saving ? (
                      <>
                        <FaSpinner className="animate-spin mr-2" />
                        {locale === 'en' ? 'Saving...' : 'កំពុងរក្សាទុក...'}
                      </>
                    ) : (
                      locale === 'en' ? 'Next' : 'បន្ត'
                    )}
                  </Button>
                </div>
              </form>
            </CardContent>
          </Card>
        </div>
      </div>
    );
  }

  // Render section1 - matching client questionnaire UI
  if (index === 'section1') {
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
          {/* Title - matching client questionnaire */}
          <div className="mb-6 px-4">
            <h2 className={`text-2xl font-bold text-primary ${locale === 'kh' ? 'font-khmer' : ''}`}>
              {t(locale, 'provider.questions.title')} {pageData?.site ? `- ${pageData.site}` : ''}
            </h2>
          </div>

          <Card className="border-0 shadow-none bg-transparent">
            <CardContent className="p-0">
              {error && (
                <Alert variant="destructive" className="mb-6 mx-4">
                  <AlertDescription>{error}</AlertDescription>
                </Alert>
              )}
              
              <form onSubmit={handleSubmit}>
                {/* Section 1: Department */}
                <div className="mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-border/50 px-4">
                  <h3 className={`text-lg sm:text-xl font-bold text-primary mb-1 sm:mb-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                    {t(locale, 'provider.questions.section1')}
                  </h3>
                </div>
                
                <div className="px-4">
                  <div className="space-y-2 mb-8">
                    <Label className={`text-base font-semibold text-muted-foreground flex items-start gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                      <span className="text-primary mt-1">•</span>
                      <span className={`flex-1 ${locale === 'kh' ? 'font-khmer' : ''}`} dangerouslySetInnerHTML={{ __html: t(locale, 'provider.questions.dept') }} />
                      <TTSButton 
                        text={t(locale, 'provider.questions.dept')} 
                        languageCode={locale}
                        audioUrl={pageData?.questions?.find(q => q.question_key === 'dept')?.[locale === 'en' ? 'audio_url_en' : 'audio_url_kh']}
                        className="ml-2"
                      />
                    </Label>
                    <div className="space-y-2 pl-6 mt-3">
                      {[1, 2, 3, 4, 5, 6, 99].map((value) => {
                        const isSelected = formData.dept === value.toString() || formData.dept === value;
                        return (
                          <label
                            key={value}
                            htmlFor={`dept_${value}`}
                            className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                              isSelected
                                ? 'border-primary bg-primary/10'
                                : 'border-border hover:bg-accent/50'
                            } ${locale === 'kh' ? 'font-khmer' : ''}`}
                          >
                            <input
                              type="radio"
                              id={`dept_${value}`}
                              name="dept"
                              value={value.toString()}
                              checked={isSelected}
                              onChange={handleInputChange}
                              className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary cursor-pointer accent-primary flex-shrink-0"
                              required
                            />
                            <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-medium text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                              {t(locale, `provider.answers.d1_${value}`)}
                            </span>
                          </label>
                        );
                      })}
                    </div>
                  </div>
                </div>

                {/* Section 2: E1-E6 Questions */}
                <div className="mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-border/50 px-4 mt-8">
                  <h3 className={`text-lg sm:text-xl font-bold text-primary mb-1 sm:mb-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                    {t(locale, 'provider.questions.section2')}
                  </h3>
                </div>
                
                <div className="px-4">
                  {/* E1 Question */}
                  <div className="space-y-2 mb-8">
                    <Label className={`text-base font-semibold text-muted-foreground flex items-start gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                      <span className="text-primary mt-1">•</span>
                      <span className={`flex-1 ${locale === 'kh' ? 'font-khmer' : ''}`}>{t(locale, 'provider.questions.e1')}</span>
                      <TTSButton 
                        text={t(locale, 'provider.questions.e1')} 
                        languageCode={locale}
                        audioUrl={pageData?.questions?.find(q => q.question_key === 'e1')?.[locale === 'en' ? 'audio_url_en' : 'audio_url_kh']}
                        className="ml-2"
                      />
                    </Label>
                    <div className="space-y-2 pl-6 mt-3">
                      {[1, 2, 98].map((value) => {
                        const isSelected = formData.e1 === value.toString() || formData.e1 === value;
                        return (
                          <label
                            key={value}
                            htmlFor={`e1_${value}`}
                            className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                              isSelected
                                ? 'border-primary bg-primary/10'
                                : 'border-border hover:bg-accent/50'
                            } ${locale === 'kh' ? 'font-khmer' : ''}`}
                          >
                            <input
                              type="radio"
                              id={`e1_${value}`}
                              name="e1"
                              value={value.toString()}
                              checked={isSelected}
                              onChange={handleInputChange}
                              className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary cursor-pointer accent-primary flex-shrink-0"
                              required
                            />
                            <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-medium text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                              {t(locale, `provider.answers.e1_${value}`)}
                            </span>
                          </label>
                        );
                      })}
                    </div>
                  </div>

                  {/* E2 Question */}
                  <div className="space-y-2 mb-8">
                    <Label className={`text-base font-semibold text-muted-foreground flex items-start gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                      <span className="text-primary mt-1">•</span>
                      <span className={`flex-1 ${locale === 'kh' ? 'font-khmer' : ''}`}>{t(locale, 'provider.questions.e2')}</span>
                      <TTSButton 
                        text={t(locale, 'provider.questions.e2')} 
                        languageCode={locale}
                        audioUrl={pageData?.questions?.find(q => q.question_key === 'e2')?.[locale === 'en' ? 'audio_url_en' : 'audio_url_kh']}
                        className="ml-2"
                      />
                    </Label>
                    <div className="space-y-2 pl-6 mt-3">
                      {[1, 2, 98].map((value) => {
                        const isSelected = formData.e2 === value.toString() || formData.e2 === value;
                        return (
                          <label
                            key={value}
                            htmlFor={`e2_${value}`}
                            className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                              isSelected
                                ? 'border-primary bg-primary/10'
                                : 'border-border hover:bg-accent/50'
                            } ${locale === 'kh' ? 'font-khmer' : ''}`}
                          >
                            <input
                              type="radio"
                              id={`e2_${value}`}
                              name="e2"
                              value={value.toString()}
                              checked={isSelected}
                              onChange={handleInputChange}
                              className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary cursor-pointer accent-primary flex-shrink-0"
                              required
                            />
                            <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-medium text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                              {t(locale, `provider.answers.e2_${value}`)}
                            </span>
                          </label>
                        );
                      })}
                    </div>
                  </div>

                  {/* E3 Question */}
                  <div className="space-y-2 mb-8">
                    <Label className={`text-base font-semibold text-muted-foreground flex items-start gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                      <span className="text-primary mt-1">•</span>
                      <span className={`flex-1 ${locale === 'kh' ? 'font-khmer' : ''}`}>{t(locale, 'provider.questions.e3')}</span>
                      <TTSButton 
                        text={t(locale, 'provider.questions.e3')} 
                        languageCode={locale}
                        audioUrl={pageData?.questions?.find(q => q.question_key === 'e3')?.[locale === 'en' ? 'audio_url_en' : 'audio_url_kh']}
                        className="ml-2"
                      />
                    </Label>
                    <div className="space-y-2 pl-6 mt-3">
                      {[1, 2, 98, 99].map((value) => {
                        const isSelected = formData.e3 === value.toString() || formData.e3 === value;
                        return (
                          <label
                            key={value}
                            htmlFor={`e3_${value}`}
                            className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                              isSelected
                                ? 'border-primary bg-primary/10'
                                : 'border-border hover:bg-accent/50'
                            } ${locale === 'kh' ? 'font-khmer' : ''}`}
                          >
                            <input
                              type="radio"
                              id={`e3_${value}`}
                              name="e3"
                              value={value.toString()}
                              checked={isSelected}
                              onChange={handleInputChange}
                              className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary cursor-pointer accent-primary flex-shrink-0"
                              required
                            />
                            <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-medium text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                              {t(locale, `provider.answers.e3_${value}`)}
                            </span>
                          </label>
                        );
                      })}
                    </div>
                  </div>

                  {/* E4 Question */}
                  <div className="space-y-2 mb-8">
                    <Label className={`text-base font-semibold text-muted-foreground flex items-start gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                      <span className="text-primary mt-1">•</span>
                      <span className={`flex-1 ${locale === 'kh' ? 'font-khmer' : ''}`}>{t(locale, 'provider.questions.e4')}</span>
                      <TTSButton 
                        text={t(locale, 'provider.questions.e4')} 
                        languageCode={locale}
                        audioUrl={pageData?.questions?.find(q => q.question_key === 'e4')?.[locale === 'en' ? 'audio_url_en' : 'audio_url_kh']}
                        className="ml-2"
                      />
                    </Label>
                    <div className="space-y-2 pl-6 mt-3">
                      {[1, 2, 3, 4].map((value) => {
                        const isSelected = formData.e4 === value.toString() || formData.e4 === value;
                        return (
                          <label
                            key={value}
                            htmlFor={`e4_${value}`}
                            className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                              isSelected
                                ? 'border-primary bg-primary/10'
                                : 'border-border hover:bg-accent/50'
                            } ${locale === 'kh' ? 'font-khmer' : ''}`}
                          >
                            <input
                              type="radio"
                              id={`e4_${value}`}
                              name="e4"
                              value={value.toString()}
                              checked={isSelected}
                              onChange={handleInputChange}
                              className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary cursor-pointer accent-primary flex-shrink-0"
                              required
                            />
                            <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-medium text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                              {t(locale, `provider.answers.e4_${value}`)}
                            </span>
                          </label>
                        );
                      })}
                    </div>
                  </div>

                  {/* E5 Question */}
                  <div className="space-y-2 mb-8">
                    <Label className={`text-base font-semibold text-muted-foreground flex items-start gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                      <span className="text-primary mt-1">•</span>
                      <span className={`flex-1 ${locale === 'kh' ? 'font-khmer' : ''}`}>{t(locale, 'provider.questions.e5')}</span>
                      <TTSButton 
                        text={t(locale, 'provider.questions.e5')} 
                        languageCode={locale}
                        audioUrl={pageData?.questions?.find(q => q.question_key === 'e5')?.[locale === 'en' ? 'audio_url_en' : 'audio_url_kh']}
                        className="ml-2"
                      />
                    </Label>
                    <div className="space-y-2 pl-6 mt-3">
                      {[1, 2, 3].map((value) => {
                        const isSelected = formData.e5 === value.toString() || formData.e5 === value;
                        return (
                          <label
                            key={value}
                            htmlFor={`e5_${value}`}
                            className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                              isSelected
                                ? 'border-primary bg-primary/10'
                                : 'border-border hover:bg-accent/50'
                            } ${locale === 'kh' ? 'font-khmer' : ''}`}
                          >
                            <input
                              type="radio"
                              id={`e5_${value}`}
                              name="e5"
                              value={value.toString()}
                              checked={isSelected}
                              onChange={handleInputChange}
                              className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary cursor-pointer accent-primary flex-shrink-0"
                              required
                            />
                            <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-medium text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                              {t(locale, `provider.answers.e5_${value}`)}
                            </span>
                          </label>
                        );
                      })}
                    </div>
                  </div>

                  {/* E6 Question */}
                  <div className="space-y-2 mb-8">
                    <Label className={`text-base font-semibold text-muted-foreground flex items-start gap-2 ${locale === 'kh' ? 'font-khmer' : ''}`}>
                      <span className="text-primary mt-1">•</span>
                      <span className={`flex-1 ${locale === 'kh' ? 'font-khmer' : ''}`}>{t(locale, 'provider.questions.e6')}</span>
                      <TTSButton 
                        text={t(locale, 'provider.questions.e6')} 
                        languageCode={locale}
                        audioUrl={pageData?.questions?.find(q => q.question_key === 'e6')?.[locale === 'en' ? 'audio_url_en' : 'audio_url_kh']}
                        className="ml-2"
                      />
                    </Label>
                    <div className="space-y-2 pl-6 mt-3">
                      {[1, 2, 3, 4, 5].map((value) => {
                        const isSelected = formData.e6 === value.toString() || formData.e6 === value;
                        return (
                          <label
                            key={value}
                            htmlFor={`e6_${value}`}
                            className={`flex items-center space-x-3 p-4 rounded-lg border cursor-pointer transition-colors ${
                              isSelected
                                ? 'border-primary bg-primary/10'
                                : 'border-border hover:bg-accent/50'
                            } ${locale === 'kh' ? 'font-khmer' : ''}`}
                          >
                            <input
                              type="radio"
                              id={`e6_${value}`}
                              name="e6"
                              value={value.toString()}
                              checked={isSelected}
                              onChange={handleInputChange}
                              className="h-5 w-5 text-primary focus:ring-2 focus:ring-primary cursor-pointer accent-primary flex-shrink-0"
                              required
                            />
                            <span className={`text-base flex-1 cursor-pointer ${isSelected ? 'font-medium text-foreground' : 'text-muted-foreground'} ${locale === 'kh' ? 'font-khmer' : ''}`}>
                              {t(locale, `provider.answers.e6_${value}`)}
                            </span>
                          </label>
                        );
                      })}
                    </div>
                  </div>
                </div>

                {/* Submit button - matching client questionnaire */}
                <div className="flex gap-4 pt-6 border-t border-border/50 px-4 pb-4">
                  <Button 
                    type="submit" 
                    disabled={saving} 
                    className="w-full h-12 text-base font-semibold"
                    size="lg"
                  >
                    {saving ? (
                      <>
                        <FaSpinner className="animate-spin mr-2" />
                        <span className={locale === 'kh' ? 'font-khmer' : ''}>{locale === 'en' ? 'Saving...' : 'កំពុងរក្សាទុក...'}</span>
                      </>
                    ) : (
                      <>
                        <span className={locale === 'kh' ? 'font-khmer' : ''}>{locale === 'en' ? 'Save & Continue' : 'រក្សាទុក & បន្ត'}</span>
                      </>
                    )}
                  </Button>
                </div>
            </form>
          </CardContent>
        </Card>
        </div>
      </div>
    );
  }

  return null;
}

