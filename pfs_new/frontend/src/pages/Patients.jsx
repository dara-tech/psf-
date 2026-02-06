import { useEffect, useState } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Label } from '../components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { Skeleton } from '../components/ui/skeleton';
import { Badge } from '../components/ui/badge';
import { ChartCard, VerticalBarChartCard } from '../components/charts';
import { FaFilter, FaChartLine, FaDatabase } from 'react-icons/fa';
import api from '../lib/api';
import { useUIStore } from '../lib/stores/uiStore';
import { t } from '../lib/translations/index';

const CHART_COLORS = [
  'hsl(var(--chart-1))',
  'hsl(var(--chart-2))',
  'hsl(var(--chart-3))',
  'hsl(var(--chart-4))',
  'hsl(var(--chart-5))',
];

export default function Patients() {
  const { locale } = useUIStore();
  const [dashboardData, setDashboardData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [filters, setFilters] = useState({
    periods: [],
    sites: [],
    kps: [],
    provinces: [],
    ages: [],
    isFiscalYear: false,
    byMonth: false,
    locale: locale || 'en'
  });
  const [availableSites, setAvailableSites] = useState([]);
  const [availableProvinces, setAvailableProvinces] = useState({});
  const [userProvinces, setUserProvinces] = useState(null); // Provinces user has access to
  const [availableKPs, setAvailableKPs] = useState({});
  const [availableAges, setAvailableAges] = useState({});
  const [availablePeriods, setAvailablePeriods] = useState([]);

  useEffect(() => {
    fetchDashboard();
  }, []);

  // Auto-select first period and fetch data when periods are loaded
  useEffect(() => {
    if (availablePeriods.length > 0 && filters.periods.length === 0) {
      const firstPeriod = availablePeriods[0].value;
      const updatedFilters = { ...filters, periods: [firstPeriod] };
      setFilters(updatedFilters);
      // Fetch dashboard with the first period
      fetchDashboardWithFilters(updatedFilters);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [availablePeriods]);

  const fetchDashboard = async () => {
    setLoading(true);
    try {
      // Send periods as array and also as comma-separated string for backward compatibility
      const paramsToSend = {
        ...filters,
        periods: filters.periods,
        period: filters.periods.length > 0 ? filters.periods.join(',') : ''
      };
      const response = await api.post('/reporting/dashboard', paramsToSend);
      if (response.data.success) {
        setDashboardData(response.data.data);
        setAvailableSites(response.data.sites || []);
        setAvailableProvinces(response.data.provinces || {});
        setUserProvinces(response.data.userProvinces || null);
        setAvailableKPs(response.data.kps || {});
        setAvailableAges(response.data.ages || {});
        setAvailablePeriods(response.data.periods || []);
        
        // Auto-select province if user has only one province assigned
        if (response.data.userProvinces && Array.isArray(response.data.userProvinces) && response.data.userProvinces.length === 1) {
          setFilters(prev => ({ ...prev, provinces: response.data.userProvinces }));
        }
      } else {
        console.error('Dashboard fetch failed:', response.data.error);
      }
    } catch (error) {
      console.error('Fetch dashboard error:', error);
      console.error('Error details:', error.response?.data);
      // Set empty data on error so UI doesn't break
      setDashboardData({
        participationChart: null,
        platformChart: null,
        kpChart: null,
        providerSatisfactionChart: null,
        serviceSatisfactionChart: null,
        patientSatisfactionChart: null,
        providerAttitudeChart: null,
        patientCommentsChart: null
      });
    } finally {
      setLoading(false);
    }
  };

  const fetchDashboardWithFilters = async (filterParams) => {
    setLoading(true);
    try {
      // Auto-select province if user has only one province assigned and not already selected
      let finalFilterParams = { ...filterParams };
      if (filterParams.provinces && filterParams.provinces.length === 0) {
        // Check if we need to auto-select province - this will be handled after first response
      }
      
      // Send periods as array and also as comma-separated string for backward compatibility
      const paramsToSend = {
        ...finalFilterParams,
        periods: finalFilterParams.periods || [],
        period: finalFilterParams.periods && finalFilterParams.periods.length > 0 ? finalFilterParams.periods.join(',') : (finalFilterParams.period || '')
      };
      console.log('Fetching dashboard with filters:', paramsToSend);
      const response = await api.post('/reporting/dashboard', paramsToSend);
      console.log('Dashboard response:', response.data);
      if (response.data.success) {
        setDashboardData(response.data.data);
        setAvailableSites(response.data.sites || []);
        setAvailableProvinces(response.data.provinces || {});
        setUserProvinces(response.data.userProvinces || null);
        setAvailableKPs(response.data.kps || {});
        setAvailableAges(response.data.ages || {});
        setAvailablePeriods(response.data.periods || []);
        console.log('Dashboard data set:', response.data.data);
        
        // Auto-select province if user has only one province assigned and not already selected
        if (response.data.userProvinces && Array.isArray(response.data.userProvinces) && response.data.userProvinces.length === 1) {
          if (!filterParams.provinces || filterParams.provinces.length === 0) {
            const updatedFilters = { ...filterParams, provinces: response.data.userProvinces };
            setFilters(updatedFilters);
            // Re-fetch with the auto-selected province
            const paramsWithProvince = {
              ...updatedFilters,
              periods: updatedFilters.periods || [],
              period: updatedFilters.periods && updatedFilters.periods.length > 0 ? updatedFilters.periods.join(',') : (updatedFilters.period || '')
            };
            const retryResponse = await api.post('/reporting/dashboard', paramsWithProvince);
            if (retryResponse.data.success) {
              setDashboardData(retryResponse.data.data);
            }
          }
        }
      } else {
        console.error('Dashboard fetch failed:', response.data.error);
      }
    } catch (error) {
      console.error('Fetch dashboard error:', error);
      console.error('Error details:', error.response?.data);
      // Set empty data on error so UI doesn't break
      setDashboardData({
        participationChart: null,
        platformChart: null,
        kpChart: null,
        providerSatisfactionChart: null,
        serviceSatisfactionChart: null,
        patientSatisfactionChart: null,
        providerAttitudeChart: null,
        patientCommentsChart: null
      });
    } finally {
      setLoading(false);
    }
  };

  const handleFilterChange = (key, value) => {
    setFilters(prev => ({ ...prev, [key]: value }));
  };

  const handleApplyFilters = () => {
    fetchDashboardWithFilters(filters);
  };

  // Helper function to aggregate quarter-based data into single values
  const aggregateData = (dataArray, key) => {
    if (!dataArray || dataArray.length === 0) return 0;
    return dataArray.reduce((sum, item) => sum + (item[key] || 0), 0);
  };

  // Helper function to calculate average percentage from quarter data
  const averagePercentage = (dataArray, key, totalKey) => {
    if (!dataArray || dataArray.length === 0) return 0;
    let totalValue = 0;
    let totalCount = 0;
    dataArray.forEach(item => {
      if (item[key] !== undefined && item[totalKey] !== undefined) {
        totalValue += item[key];
        totalCount += item[totalKey];
      }
    });
    return totalCount > 0 ? Math.round((totalValue / totalCount) * 100) : 0;
  };

  // Aggregate platform chart data (Tablet vs QR Code)
  const getAggregatedPlatformData = () => {
    if (!dashboardData?.platformChart || dashboardData.platformChart.length === 0) return null;
    const totalTablet = aggregateData(dashboardData.platformChart, 'odk');
    const totalQR = aggregateData(dashboardData.platformChart, 'online');
    return [
      { name: locale === 'kh' ? 'Tablet' : 'Tablet', value: totalTablet },
      { name: locale === 'kh' ? 'QR Code' : 'QR Code', value: totalQR }
    ];
  };

  // Aggregate provider satisfaction data
  const getAggregatedProviderSatisfaction = () => {
    if (!dashboardData?.providerSatisfactionChart || dashboardData.providerSatisfactionChart.length === 0) return null;
    const data = dashboardData.providerSatisfactionChart;
    // Calculate average percentages
    const avgOverall = Math.round(data.reduce((sum, item) => sum + (item.overall || 0), 0) / data.length);
    const avgReceptionist = Math.round(data.reduce((sum, item) => sum + (item.receptionist || 0), 0) / data.length);
    const avgCounselor = Math.round(data.reduce((sum, item) => sum + (item.counselor || 0), 0) / data.length);
    const avgDoctor = Math.round(data.reduce((sum, item) => sum + (item.doctor || 0), 0) / data.length);
    const avgPharmacist = Math.round(data.reduce((sum, item) => sum + (item.pharmacist || 0), 0) / data.length);
    
    return [
      { name: locale === 'kh' ? 'ការពេញចិត្តជារួម' : 'Overall Satisfaction', value: avgOverall },
      { name: locale === 'kh' ? 'អ្នកទទួលចុះឈ្មោះ' : 'Registrar/Receptionist', value: avgReceptionist },
      { name: locale === 'kh' ? 'អ្នកផ្តល់ប្រឹក្សា' : 'Counselor', value: avgCounselor },
      { name: locale === 'kh' ? 'គ្រូពេទ្យ' : 'Doctor', value: avgDoctor },
      { name: locale === 'kh' ? 'ឱសថការី' : 'Pharmacist', value: avgPharmacist }
    ];
  };

  // Aggregate service satisfaction data (Quality of Care)
  const getAggregatedServiceQuality = () => {
    if (!dashboardData?.serviceSatisfactionChart || dashboardData.serviceSatisfactionChart.length === 0) return null;
    const data = dashboardData.serviceSatisfactionChart;
    // For quality of care, we'll use service satisfaction data but map to quality metrics
    const avgCounseling = Math.round(data.reduce((sum, item) => sum + (item.anc || 0), 0) / data.length);
    const avgPhysical = Math.round(data.reduce((sum, item) => sum + (item.lab || 0), 0) / data.length);
    const avgBloodTest = Math.round(data.reduce((sum, item) => sum + (item.lab || 0), 0) / data.length);
    const avgMedication = Math.round(data.reduce((sum, item) => sum + (item.psycho || 0), 0) / data.length);
    
    return [
      { name: locale === 'kh' ? 'ការប្រឹក្សាយោបល់' : 'Counseling', value: avgCounseling },
      { name: locale === 'kh' ? 'ការពិនិត្យរាងកាយ' : 'Physical Examination', value: avgPhysical },
      { name: locale === 'kh' ? 'ការធ្វើតេស្តឈាម' : 'Blood Test', value: avgBloodTest },
      { name: locale === 'kh' ? 'ការផ្តល់ថ្នាំ' : 'Medication Dispensing', value: avgMedication }
    ];
  };

  // Aggregate other services satisfaction
  const getAggregatedOtherServices = () => {
    if (!dashboardData?.serviceSatisfactionChart || dashboardData.serviceSatisfactionChart.length === 0) return null;
    const data = dashboardData.serviceSatisfactionChart;
    return [
      { name: locale === 'kh' ? 'សុខភាពផ្លូវចិត្ត' : 'Mental Health', value: Math.round(data.reduce((sum, item) => sum + (item.psycho || 0), 0) / data.length) },
      { name: locale === 'kh' ? 'មន្ទីរពិសោធន៍' : 'Laboratory', value: Math.round(data.reduce((sum, item) => sum + (item.lab || 0), 0) / data.length) },
      { name: locale === 'kh' ? 'ការប្រឹក្សាយោបល់' : 'Counseling', value: Math.round(data.reduce((sum, item) => sum + (item.anc || 0), 0) / data.length) },
      { name: locale === 'kh' ? 'ការប្រឹក្សាយោបល់ភ្នែក' : 'Eye Counseling', value: Math.round(data.reduce((sum, item) => sum + (item.sti || 0), 0) / data.length) },
      { name: locale === 'kh' ? 'ការប្រឹក្សាយោបល់ចិត្តសាស្ត្រ' : 'Psychological Counseling', value: Math.round(data.reduce((sum, item) => sum + (item.psycho || 0), 0) / data.length) }
    ];
  };

  return (
    <div className="min-h-screen">
      {/* Header Section with Gradient Accent */}
      <div className="mb-8 relative">
        <div className="absolute inset-0 bg-gradient-to-r from-primary/10 via-primary/5 to-transparent rounded-2xl blur-3xl -z-10"></div>
        <div className="relative flex items-center justify-between">
          <div className="flex items-start gap-3 mb-3 flex-1">
            <div className="w-1 bg-gradient-custom rounded-full flex-shrink-0" style={{ minHeight: '3.5rem' }}></div>
            <div className="flex-1 min-w-0">
              <h1 className="text-4xl font-bold tracking-tight text-foreground break-words leading-tight">
                {t(locale, 'admin.common.dashboard')}
              </h1>
              <p className="text-muted-foreground mt-1.5 text-lg break-words">
                Patient reporting analytics and visualizations
              </p>
            </div>
          </div>
          <Badge variant="secondary" className="text-sm px-3 py-1.5 ml-4 bg-primary/10 text-primary border-primary/20">
            <FaChartLine className="h-3 w-3 mr-1" />
            {t(locale, 'admin.common.liveData')}
          </Badge>
        </div>
      </div>

      {/* Filters with Modern Glass Effect */}
      <Card className="border-primary/20 shadow-xl shadow-primary/5 backdrop-blur-sm bg-card/95 mb-8 transition-all duration-300 hover:shadow-2xl hover:shadow-primary/10">
        <CardHeader className="pb-4">
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
              <FaFilter className="h-4 w-4 text-primary" />
            </div>
            <CardTitle className="text-xl">{t(locale, 'admin.common.filters')}</CardTitle>
          </div>
          <CardDescription className="mt-1">{t(locale, 'admin.common.configureFilters')}</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="flex flex-wrap items-end gap-4">
            <div className="flex-1 min-w-[200px] space-y-2">
              <Label htmlFor="period" className="text-sm font-semibold text-foreground">{t(locale, 'admin.common.period')} *</Label>
              <div className="space-y-2">
                {/* Selected Periods as Chips */}
                {filters.periods.length > 0 && (
                  <div className="flex flex-wrap gap-2 p-3 border border-primary/20 rounded-lg bg-gradient-to-br from-primary/5 to-primary/0 min-h-[42px] backdrop-blur-sm">
                    {filters.periods.map((periodValue) => {
                      const period = availablePeriods.find(p => p.value === periodValue);
                      const periodLabel = period?.label || periodValue;
                      return (
                        <div
                          key={periodValue}
                          className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-primary to-primary/90 text-primary-foreground rounded-lg text-sm font-medium shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/30 transition-all duration-200"
                        >
                          <span>{periodLabel}</span>
                          <button
                            type="button"
                            onClick={() => {
                              const newPeriods = filters.periods.filter(p => p !== periodValue);
                              handleFilterChange('periods', newPeriods);
                            }}
                            className="ml-0.5 hover:bg-primary-foreground/20 rounded-full p-0.5 transition-colors"
                          >
                            <span className="sr-only">Remove</span>
                            <svg className="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                              <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd" />
                            </svg>
                          </button>
                        </div>
                      );
                    })}
                  </div>
                )}
                {/* Period Selector */}
                <Select
                  value=""
                  onValueChange={(value) => {
                    if (value && !filters.periods.includes(value)) {
                      handleFilterChange('periods', [...filters.periods, value]);
                    }
                  }}
                >
                  <SelectTrigger id="period" className={`border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm ${filters.periods.length > 0 ? 'mt-2' : ''}`}>
                    <SelectValue placeholder={t(locale, 'admin.common.selectPeriod')} />
                  </SelectTrigger>
                  <SelectContent className="max-h-[240px] backdrop-blur-sm bg-card border-primary/10">
                    {availablePeriods
                      .filter(period => !filters.periods.includes(period.value))
                      .map((period) => (
                        <SelectItem key={period.value} value={period.value} className="hover:bg-primary/10 focus:bg-primary/10">
                          {period.label}
                        </SelectItem>
                      ))}
                  </SelectContent>
                </Select>
              </div>
            </div>
            <div className="flex-1 min-w-[160px] space-y-2">
              <Label htmlFor="sites" className="text-sm font-semibold text-foreground">{t(locale, 'admin.common.sites')}</Label>
              <Select
                value={filters.sites[0] || '*'}
                onValueChange={(value) => handleFilterChange('sites', [value])}
              >
                <SelectTrigger id="sites" className="border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm">
                  <SelectValue placeholder={t(locale, 'admin.common.selectSite')} />
                </SelectTrigger>
                <SelectContent className="backdrop-blur-sm bg-card border-primary/10">
                  <SelectItem value="*">{t(locale, 'admin.common.allSites')}</SelectItem>
                  {availableSites.map((site) => (
                    <SelectItem key={site} value={site} className="hover:bg-primary/10 focus:bg-primary/10">
                      {site}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="flex-1 min-w-[160px] space-y-2">
              <Label htmlFor="kps" className="text-sm font-semibold text-foreground">{t(locale, 'admin.common.keyPopulation')}</Label>
              <Select
                value={filters.kps[0] || 'all'}
                onValueChange={(value) => handleFilterChange('kps', value === 'all' ? [] : [value])}
              >
                <SelectTrigger id="kps" className="border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm">
                  <SelectValue placeholder={t(locale, 'admin.common.selectKP')} />
                </SelectTrigger>
                <SelectContent className="backdrop-blur-sm bg-card border-primary/10">
                  <SelectItem value="all">{t(locale, 'admin.common.all')}</SelectItem>
                  {Object.entries(availableKPs).map(([key, label]) => (
                    <SelectItem key={key} value={key} className="hover:bg-primary/10 focus:bg-primary/10">
                      {label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="flex-1 min-w-[160px] space-y-2">
              <Label htmlFor="provinces" className="text-sm font-semibold text-foreground">{t(locale, 'admin.common.province')}</Label>
              <Select
                value={filters.provinces[0] || 'all'}
                onValueChange={(value) => handleFilterChange('provinces', value === 'all' ? [] : [value])}
              >
                <SelectTrigger id="provinces" className="border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm">
                  <SelectValue placeholder={t(locale, 'admin.common.selectProvince')} />
                </SelectTrigger>
                <SelectContent className="backdrop-blur-sm bg-card border-primary/10">
                  <SelectItem value="all">{t(locale, 'admin.common.all')}</SelectItem>
                  {Object.entries(availableProvinces)
                    .filter(([key]) => {
                      // If userProvinces is null, show all (admin or all sites permission)
                      if (userProvinces === null) return true;
                      // Otherwise, only show provinces user has access to
                      return userProvinces && userProvinces.includes(key);
                    })
                    .map(([key, label]) => (
                      <SelectItem key={key} value={key} className="hover:bg-primary/10 focus:bg-primary/10">
                        {label}
                      </SelectItem>
                    ))}
                </SelectContent>
              </Select>
            </div>
            <div className="flex-1 min-w-[160px] space-y-2">
              <Label htmlFor="ages" className="text-sm font-semibold text-foreground">{t(locale, 'admin.common.ageRange')}</Label>
              <Select
                value={filters.ages[0] || 'all'}
                onValueChange={(value) => handleFilterChange('ages', value === 'all' ? [] : [value])}
              >
                <SelectTrigger id="ages" className="border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm">
                  <SelectValue placeholder={t(locale, 'admin.common.selectAge')} />
                </SelectTrigger>
                <SelectContent className="backdrop-blur-sm bg-card border-primary/10">
                  <SelectItem value="all">{t(locale, 'admin.common.all')}</SelectItem>
                  {Object.entries(availableAges).map(([key, label]) => (
                    <SelectItem key={key} value={key} className="hover:bg-primary/10 focus:bg-primary/10">
                      {label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            
            <div className="flex items-center gap-3 px-4 py-2 rounded-lg border border-border/50 bg-background/50 hover:bg-primary/5 transition-colors h-[42px]">
              <input
                type="checkbox"
                id="fiscalYear"
                checked={filters.isFiscalYear}
                onChange={(e) => handleFilterChange('isFiscalYear', e.target.checked)}
                className="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-2 cursor-pointer"
              />
              <Label htmlFor="fiscalYear" className="cursor-pointer text-sm font-medium whitespace-nowrap">{t(locale, 'admin.common.fiscalYear')}</Label>
            </div>
            
            <div className="flex items-center gap-3 px-4 py-2 rounded-lg border border-border/50 bg-background/50 hover:bg-primary/5 transition-colors h-[42px]">
              <input
                type="checkbox"
                id="byMonth"
                checked={filters.byMonth}
                onChange={(e) => handleFilterChange('byMonth', e.target.checked)}
                className="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-2 cursor-pointer"
              />
              <Label htmlFor="byMonth" className="cursor-pointer text-sm font-medium whitespace-nowrap">{t(locale, 'admin.common.byMonth')}</Label>
            </div>

            <Button 
              onClick={handleApplyFilters} 
              className="bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/30 transition-all duration-300 text-white font-semibold px-8 py-2.5 rounded-lg h-[42px] whitespace-nowrap"
            >
              <span className="flex items-center gap-2">
                {t(locale, 'admin.common.applyFilters')}
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </span>
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* Charts Grid */}
      {loading ? (
        <div className="grid gap-6 md:grid-cols-2">
          {[1, 2, 3, 4].map((i) => (
            <Card key={i} className="border-primary/10 shadow-lg backdrop-blur-sm bg-card/95">
              <CardHeader>
                <Skeleton className="h-6 w-48 bg-primary/10" />
              </CardHeader>
              <CardContent>
                <Skeleton className="h-64 w-full bg-primary/10" />
              </CardContent>
            </Card>
          ))}
        </div>
      ) : dashboardData ? (
        <div className="space-y-6">
          <div className="grid gap-6 grid-cols-1 md:grid-cols-2">
          {/* Number of Participants Chart */}
          {getAggregatedPlatformData() && (
            <ChartCard
              title={locale === 'kh' ? 'ចំនួនអ្នកចូលរួម' : 'Number of Participants'}
              data={getAggregatedPlatformData()}
              dataKey="value"
              colorIndex={0}
              locale={locale}
              defaultChartType="bar"
            />
          )}

          {/* Patient Satisfaction (ART Service) % */}
          {getAggregatedProviderSatisfaction() && (
            <ChartCard
              title={locale === 'kh' ? 'ការពេញចិត្តរបស់អ្នកជំងឺ (សេវា ART) %' : 'Patient Satisfaction (ART Service) %'}
              data={getAggregatedProviderSatisfaction()}
              dataKey="value"
              colorIndex={1}
              angle={-45}
              domain={[0, 100]}
              locale={locale}
              defaultChartType="bar"
            />
          )}

          {/* Quality of Care Service % */}
          {getAggregatedServiceQuality() && (
            <ChartCard
              title={locale === 'kh' ? 'គុណភាពនៃសេវាថែទាំ %' : 'Quality of Care Service %'}
              data={getAggregatedServiceQuality()}
              dataKey="value"
              colorIndex={2}
              angle={-45}
              domain={[0, 100]}
              locale={locale}
              defaultChartType="bar"
            />
          )}

          {/* Patient Satisfaction (Other Services) % */}
          {getAggregatedOtherServices() && (
            <ChartCard
              title={locale === 'kh' ? 'ការពេញចិត្តរបស់អ្នកជំងឺ (សេវាផ្សេងទៀត) %' : 'Patient Satisfaction (Other Services) %'}
              data={getAggregatedOtherServices()}
              dataKey="value"
              colorIndex={3}
              angle={-45}
              domain={[0, 100]}
              locale={locale}
              defaultChartType="bar"
            />
          )}
          </div>

          {/* Number and Percentage of Patients Completing Feedback */}
          {dashboardData.patientCommentsChart && dashboardData.patientCommentsChart.total > 0 && (
            <VerticalBarChartCard
              title={
                locale === 'kh' 
                  ? `ចំនួន និងភាគរយ អ្នកជំងឺបំពេញមតិយោបល់ និងសំណូមពរ (#${dashboardData.patientCommentsChart.total})`
                  : `Number and Percentage of Patients Completing Feedback and Suggestions (#${dashboardData.patientCommentsChart.total})`
              }
              data={[
                { 
                  name: locale === 'kh' ? 'កាត់មួយពេលរាំ (29%)' : 'Cut once a week (29%)', 
                  value: dashboardData.patientCommentsChart.reduceWaitingTime,
                  percentage: Math.round((dashboardData.patientCommentsChart.reduceWaitingTime / dashboardData.patientCommentsChart.total) * 100)
                },
                { 
                  name: locale === 'kh' ? 'អ្នកគ្រប់ប្រឹក្សា (13%)' : 'Counselor (13%)', 
                  value: dashboardData.patientCommentsChart.moreFriendlyProvider,
                  percentage: Math.round((dashboardData.patientCommentsChart.moreFriendlyProvider / dashboardData.patientCommentsChart.total) * 100)
                },
                { 
                  name: locale === 'kh' ? 'សុភាព និងការព្យា (7%)' : 'Politeness and Treatment (7%)', 
                  value: dashboardData.patientCommentsChart.staffPresent,
                  percentage: Math.round((dashboardData.patientCommentsChart.staffPresent / dashboardData.patientCommentsChart.total) * 100)
                },
                { 
                  name: locale === 'kh' ? 'ការប្រឹក្សាយោបល់ភ្នែក (19%)' : 'Eye Counseling (19%)', 
                  value: dashboardData.patientCommentsChart.cleanWaitingRoom,
                  percentage: Math.round((dashboardData.patientCommentsChart.cleanWaitingRoom / dashboardData.patientCommentsChart.total) * 100)
                },
                { 
                  name: locale === 'kh' ? 'ការប្រឹក្សាយោបល់ចិត្តសាស្ត្រ (25%)' : 'Psychological Counseling (25%)', 
                  value: dashboardData.patientCommentsChart.serviceEvery6Month,
                  percentage: Math.round((dashboardData.patientCommentsChart.serviceEvery6Month / dashboardData.patientCommentsChart.total) * 100)
                },
                { 
                  name: locale === 'kh' ? 'សរុប (74%)' : 'Total (74%)', 
                  value: dashboardData.patientCommentsChart.total,
                  percentage: 74
                }
              ]}
              dataKey="value"
              colorIndex={0}
              height={400}
              locale={locale}
              formatter={(value, name) => [`${value} (${Math.round((value / dashboardData.patientCommentsChart.total) * 100)}%)`, name]}
            />
          )}
        </div>
      ) : (
        <div className="space-y-6">
          <Card className="border-primary/20 shadow-xl shadow-primary/5 backdrop-blur-sm bg-card/95">
            <CardHeader>
              <CardTitle>Dashboard Overview</CardTitle>
              <CardDescription>Select filters to view analytics and charts</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="py-16 text-center">
                <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
                  <FaChartLine className="h-8 w-8 text-primary/60" />
                </div>
                <h3 className="text-lg font-semibold mb-2">No Data Selected</h3>
                <p className="text-sm text-muted-foreground max-w-md mx-auto mb-4 font-medium">
                  To view dashboard analytics, please:
                </p>
                <ol className="text-sm text-muted-foreground text-left max-w-md mx-auto space-y-2 list-decimal list-inside">
                  <li>Select a period from the dropdown (e.g., Q1 2024)</li>
                  <li>Optionally select sites, KP, province, or age filters</li>
                  <li>Toggle Fiscal Year or By Month if needed</li>
                  <li>Click "Apply Filters" to generate charts</li>
                </ol>
              </div>
            </CardContent>
          </Card>

          {/* Quick Stats Preview */}
          <div className="grid gap-4 md:grid-cols-4">
            <Card className="border-primary/20 shadow-lg shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-xl hover:shadow-primary/10 hover:border-primary/30">
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Total Charts</CardTitle>
                <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
                  <FaChartLine className="h-4 w-4 text-primary" />
                </div>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">8</div>
                <p className="text-xs text-muted-foreground">Available visualizations</p>
              </CardContent>
            </Card>
            <Card className="border-primary/20 shadow-lg shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-xl hover:shadow-primary/10 hover:border-primary/30">
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Filter Options</CardTitle>
                <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
                  <FaFilter className="h-4 w-4 text-primary" />
                </div>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">5</div>
                <p className="text-xs text-muted-foreground">Filter categories</p>
              </CardContent>
            </Card>
            <Card className="border-primary/20 shadow-lg shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-xl hover:shadow-primary/10 hover:border-primary/30">
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Data Range</CardTitle>
                <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
                  <FaDatabase className="h-4 w-4 text-primary" />
                </div>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">2020+</div>
                <p className="text-xs text-muted-foreground">Historical data available</p>
              </CardContent>
            </Card>
            <Card className="border-primary/20 shadow-lg shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-xl hover:shadow-primary/10 hover:border-primary/30">
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Status</CardTitle>
                <div className="h-2 w-2 rounded-full bg-primary shadow-lg shadow-primary/50" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">Ready</div>
                <p className="text-xs text-muted-foreground">System operational</p>
              </CardContent>
            </Card>
          </div>
        </div>
      )}
    </div>
  );
}
