import { Button } from './ui/button';
import { useUIStore } from '../lib/stores/uiStore';
import { cn } from '../lib/utils';

const LANGUAGES = {
  en: { label: 'EN' },
  kh: { label: 'ខ្មែរ' }
};

export default function LanguageToggle() {
  const { locale, setLocale } = useUIStore();
  
  const current = LANGUAGES[locale] || LANGUAGES.en;
  const next = LANGUAGES[locale === 'en' ? 'kh' : 'en'];

  return (
    <Button
      variant="ghost"
      onClick={() => setLocale(locale === 'en' ? 'kh' : 'en')}
      className={cn(
        'h-10  bg-background/80 ',
        ' transition-all duration-200​'
      )}
      title={`Switch to ${next.label}`}
    >
      <span className="font-medium text-muted-foreground">{current.label}</span>
    </Button>
  );
}
