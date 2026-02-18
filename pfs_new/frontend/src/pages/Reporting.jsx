import { useEffect, useState, useMemo } from 'react';
import { useReportingStore } from '../lib/stores/reportingStore';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '../components/ui/table';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { Skeleton } from '../components/ui/skeleton';
import { Badge } from '../components/ui/badge';
import EmptyState from '../components/EmptyState';
import { ChartCard } from '../components/charts';
import { FaFileExport, FaDownload, FaFilter, FaCalendarAlt, FaSpinner, FaInfoCircle } from 'react-icons/fa';
import api from '../lib/api';
import { useUIStore } from '../lib/stores/uiStore';
import { t } from '../lib/translations/index';

export default function Reporting() {
  const { locale } = useUIStore();
  const { tableData, sites, loading, fetchTable } = useReportingStore();
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');
  const [selectedSites, setSelectedSites] = useState(['*']);
  const [filteredData, setFilteredData] = useState([]);
  const [isFiltered, setIsFiltered] = useState(false);
  const [filterLoading, setFilterLoading] = useState(false);
  const [filterError, setFilterError] = useState('');

  useEffect(() => {
    fetchTable(locale || 'en');
    // Set default dates (last 30 days)
    const today = new Date();
    const lastMonth = new Date();
    lastMonth.setMonth(today.getMonth() - 1);
    setEndDate(today.toISOString().split('T')[0]);
    setStartDate(lastMonth.toISOString().split('T')[0]);
  }, [fetchTable, locale]);

  const handleFilter = async () => {
    if (!startDate || !endDate) {
      setFilterError(t(locale, 'admin.common.pleaseSelectBothDates'));
      return;
    }

    if (new Date(startDate) > new Date(endDate)) {
      setFilterError(t(locale, 'admin.common.startDateBeforeEndDate'));
      return;
    }

    setFilterLoading(true);
    setFilterError('');
    setIsFiltered(false);

    try {
      const response = await api.post('/reporting/table', {
        startdate: startDate,
        enddate: endDate,
        sites: selectedSites.length > 0 ? selectedSites : ['*'],
        locale: locale || 'en'
      });

      if (response.data.success) {
        setFilteredData(response.data.data || []);
        setIsFiltered(true);
        if (response.data.data && response.data.data.length === 0) {
          setFilterError(t(locale, 'admin.common.noDataForDateRange'));
        }
      } else {
        setFilterError(response.data.error || t(locale, 'admin.common.failedToFetchData'));
      }
    } catch (error) {
      console.error('Filter error:', error);
      setFilterError(error.response?.data?.error || error.message || t(locale, 'admin.common.failedToFilterData'));
    } finally {
      setFilterLoading(false);
    }
  };

  const handleExport = () => {
    // Convert data to CSV
    if (filteredData.length === 0) {
      alert(t(locale, 'admin.common.noDataToExport'));
      return;
    }

    const headers = Object.keys(filteredData[0]);
    const csvContent = [
      headers.join(','),
      ...filteredData.map(row => 
        headers.map(header => {
          const value = row[header] || '';
          return `"${String(value).replace(/"/g, '""')}"`;
        }).join(',')
      )
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    
    // Check if browser supports download attribute
    const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
    
    if (isSafari || window.navigator.msSaveOrOpenBlob) {
      // Safari and IE fallback
      if (window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveOrOpenBlob(blob, `patient-report-${startDate}-to-${endDate}.csv`);
      } else {
        // Safari: open in new window
        const url = window.URL.createObjectURL(blob);
        const newWindow = window.open(url, '_blank');
        if (!newWindow) {
          // Popup blocked, fallback to download link
          const link = document.createElement('a');
          link.href = url;
          link.download = `patient-report-${startDate}-to-${endDate}.csv`;
          link.style.display = 'none';
          document.body.appendChild(link);
          link.click();
          setTimeout(() => {
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
          }, 100);
        } else {
          setTimeout(() => window.URL.revokeObjectURL(url), 1000);
        }
      }
    } else {
      // Standard approach for Chrome, Firefox, etc. (improved for Mac)
      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      const timestamp = `${startDate}-to-${endDate}`;
      link.download = `patient-report-${timestamp}.csv`;
      link.style.display = 'none';
      document.body.appendChild(link);
      
      // Trigger download
      link.click();
      
      // Delay cleanup to ensure download starts (especially important for Mac)
      setTimeout(() => {
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
      }, 200);
    }
  };

  const displayData = isFiltered ? filteredData : [];

  // Calculate chart data from filtered data
  const chartData = useMemo(() => {
    if (!isFiltered || displayData.length === 0) {
      return {
        bySite: null,
        byPlatform: null,
        byDate: null,
        byAcknowledge: null
      };
    }

    // Data by Site
    const siteCounts = {};
    displayData.forEach(row => {
      const site = row.site || row.sitename || 'Unknown';
      siteCounts[site] = (siteCounts[site] || 0) + 1;
    });
    const bySite = Object.entries(siteCounts)
      .map(([name, value]) => ({ name, value }))
      .sort((a, b) => b.value - a.value);

    // Data by Platform
    const platformCounts = { 'ODK': 0, 'Online': 0 };
    displayData.forEach(row => {
      const platform = row.META_INSTANCE_ID ? 'ODK' : 'Online';
      platformCounts[platform] = (platformCounts[platform] || 0) + 1;
    });
    const byPlatform = Object.entries(platformCounts)
      .map(([name, value]) => ({ name, value }))
      .filter(item => item.value > 0);

    // Data by Date (monthly aggregation)
    const dateCounts = {};
    displayData.forEach(row => {
      const startDate = row.START || row.start;
      if (startDate) {
        try {
          const date = new Date(startDate);
          const monthKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
          dateCounts[monthKey] = (dateCounts[monthKey] || 0) + 1;
        } catch (e) {
          // Skip invalid dates
        }
      }
    });
    const byDate = Object.entries(dateCounts)
      .map(([name, value]) => ({ name, value }))
      .sort((a, b) => a.name.localeCompare(b.name));

    // Data by Acknowledge Status
    const acknowledgeCounts = { 
      [t(locale, 'admin.common.agree')]: 0, 
      [t(locale, 'admin.common.disagree')]: 0 
    };
    displayData.forEach(row => {
      // Get ACKNOWLEDGE value from any case variation (explicitly check for undefined, not using ??)
      const acknowledge = row.ACKNOWLEDGE !== undefined ? row.ACKNOWLEDGE : 
                         (row.acknowledge !== undefined ? row.acknowledge : 
                         (row.Acknowledge !== undefined ? row.Acknowledge : null));
      
      // ACKNOWLEDGE: 1 = agree (consented), 0 = disagree (didn't consent)
      // Handle null/undefined before string conversion
      let isAgree = false;
      if (acknowledge !== null && acknowledge !== undefined) {
        isAgree = acknowledge === 1 || acknowledge === '1' || String(acknowledge).trim() === '1';
      }
      const status = isAgree
        ? t(locale, 'admin.common.agree') 
        : t(locale, 'admin.common.disagree');
      acknowledgeCounts[status] = (acknowledgeCounts[status] || 0) + 1;
    });
    const byAcknowledge = Object.entries(acknowledgeCounts)
      .map(([name, value]) => ({ name, value }))
      .filter(item => item.value > 0);

    return {
      bySite: bySite.length > 0 ? bySite : null,
      byPlatform: byPlatform.length > 0 ? byPlatform : null,
      byDate: byDate.length > 0 ? byDate : null,
      byAcknowledge: byAcknowledge.length > 0 ? byAcknowledge : null
    };
  }, [displayData, isFiltered, locale]);

  return (
    <div className="min-h-screen ">
      {/* Header Section with Gradient Accent */}
      <div className="mb-8 relative">
        <div className="absolute inset-0 bg-gradient-to-r from-primary/10 via-primary/5 to-transparent rounded-2xl blur-3xl -z-10"></div>
        <div className="relative flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
          <div className="flex items-start gap-3 flex-1">
            <div className="w-1 bg-gradient-to-b from-primary to-primary/60 rounded-full flex-shrink-0" style={{ minHeight: '3.5rem' }}></div>
            <div className="flex-1 min-w-0">
              <h1 className="text-4xl font-bold tracking-tight text-foreground break-words leading-tight">
                {t(locale, 'admin.common.patientReporting')}
              </h1>
              <p className="text-muted-foreground mt-1.5 text-lg break-words">
                {t(locale, 'admin.common.viewExportPatientData')}
              </p>
            </div>
        </div>
          <div className="flex items-center gap-2 sm:ml-4">
            <Button
              onClick={handleExport}
              variant="default"
              className="gap-2 shadow-md hover:shadow-lg transition-shadow"
              disabled={displayData.length === 0 || filterLoading}
            >
              <FaDownload className="h-4 w-4" />
              {t(locale, 'admin.common.exportCSV')}
            </Button>
          </div>
        </div>
      </div>

      {/* Filter Card with Modern Glass Effect */}
      <Card className="border-primary/20 shadow-xl shadow-primary/5 backdrop-blur-sm bg-card/95 mb-8 transition-all duration-300 hover:shadow-2xl hover:shadow-primary/10">
        <CardHeader className="pb-4">
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
              <FaCalendarAlt className="h-4 w-4 text-primary" />
            </div>
            <CardTitle className="text-xl">{t(locale, 'admin.common.filters')}</CardTitle>
          </div>
          <CardDescription className="mt-1">{t(locale, 'admin.common.selectDateRangeSites')}</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="flex flex-wrap items-end gap-4">
            <div className="flex-1 min-w-[180px] space-y-2">
              <Label htmlFor="startdate" className="text-sm font-semibold text-foreground">{t(locale, 'admin.common.startDate')}</Label>
              <Input
                id="startdate"
                type="date"
                value={startDate}
                onChange={(e) => {
                  setStartDate(e.target.value);
                  setFilterError('');
                  setIsFiltered(false);
                }}
                className="w-full border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm"
                disabled={filterLoading}
              />
            </div>
            <div className="flex-1 min-w-[180px] space-y-2">
              <Label htmlFor="enddate" className="text-sm font-semibold text-foreground">{t(locale, 'admin.common.endDate')}</Label>
              <Input
                id="enddate"
                type="date"
                value={endDate}
                onChange={(e) => {
                  setEndDate(e.target.value);
                  setFilterError('');
                  setIsFiltered(false);
                }}
                className="w-full border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm"
                disabled={filterLoading}
                max={new Date().toISOString().split('T')[0]}
              />
            </div>
            <div className="flex-1 min-w-[180px] space-y-2">
              <Label htmlFor="sites" className="text-sm font-semibold text-foreground">{t(locale, 'admin.common.sites')}</Label>
              <Select
                value={selectedSites.length > 0 ? selectedSites[0] : '*'}
                onValueChange={(value) => {
                  setSelectedSites(value === '*' ? ['*'] : [value]);
                  setFilterError('');
                  setIsFiltered(false);
                }}
                disabled={filterLoading}
              >
                <SelectTrigger id="sites" className="border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm">
                  <SelectValue placeholder={t(locale, 'admin.common.selectSites')} />
                </SelectTrigger>
                <SelectContent className="backdrop-blur-sm bg-card border-primary/10">
                  <SelectItem value="*">{t(locale, 'admin.common.allSites')}</SelectItem>
                  {sites.map((site) => (
                    <SelectItem key={site} value={site} className="hover:bg-primary/10 focus:bg-primary/10">
                      {site}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            
            <Button 
              onClick={handleFilter}
              variant="default"
              className="gap-2 shadow-md hover:shadow-lg transition-shadow h-[42px] whitespace-nowrap"
              disabled={filterLoading || !startDate || !endDate}
            >
              {filterLoading ? (
                <span className="flex items-center gap-2">
                  <FaSpinner className="h-4 w-4 animate-spin" />
                  {t(locale, 'admin.common.loading')}
                </span>
              ) : (
                <span className="flex items-center gap-2">
                  <FaFilter className="h-4 w-4" />
                  {t(locale, 'admin.common.applyFilters')}
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                  </svg>
                </span>
              )}
            </Button>
          </div>
          
          {filterError && (
            <div className="mt-4 p-3 rounded-lg bg-destructive/10 border border-destructive/20">
              <p className="text-sm text-destructive font-medium">{filterError}</p>
            </div>
          )}
        </CardContent>
      </Card>

      {/* Stats */}
      <div className="grid gap-4 md:grid-cols-3 mb-8">
        <Card className="border-primary/20 shadow-lg shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-xl hover:shadow-primary/10 hover:border-primary/30">
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">{t(locale, 'admin.common.totalRecords')}</CardTitle>
            <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
              <FaFileExport className="h-4 w-4 text-primary" />
            </div>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{displayData.length}</div>
            <p className="text-xs text-muted-foreground">{t(locale, 'admin.common.patientRecords')}</p>
          </CardContent>
        </Card>
        <Card className="border-primary/20 shadow-lg shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-xl hover:shadow-primary/10 hover:border-primary/30">
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">{t(locale, 'admin.common.dateRange')}</CardTitle>
            <Badge variant="secondary" className="bg-primary/10 text-primary border-primary/20">
              {startDate && endDate ? `${startDate} to ${endDate}` : t(locale, 'admin.common.notSet')}
            </Badge>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">
              {startDate && endDate ? Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24)) : '-'}
            </div>
            <p className="text-xs text-muted-foreground">{t(locale, 'admin.common.days')}</p>
          </CardContent>
        </Card>
        <Card className="border-primary/20 shadow-lg shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-xl hover:shadow-primary/10 hover:border-primary/30">
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">{t(locale, 'admin.common.status')}</CardTitle>
            <div className="h-2 w-2 rounded-full bg-primary shadow-lg shadow-primary/50" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{t(locale, 'admin.common.ready')}</div>
            <p className="text-xs text-muted-foreground">{t(locale, 'admin.common.systemReady')}</p>
          </CardContent>
        </Card>
      </div>

      {/* Charts Grid */}
      {isFiltered && displayData.length > 0 && (
        <div className="mb-8 space-y-6">
          <div className="grid gap-6 grid-cols-1 md:grid-cols-2">
            {/* Records by Site */}
            {chartData.bySite && (
              <ChartCard
                title={locale === 'kh' ? 'កំណត់ត្រាតាមតំបន់' : 'Records by Site'}
                data={chartData.bySite}
                dataKey="value"
                colorIndex={0}
                locale={locale}
                defaultChartType="bar"
              />
            )}

            {/* Records by Platform */}
            {chartData.byPlatform && (
              <ChartCard
                title={locale === 'kh' ? 'កំណត់ត្រាតាមវេទិកា' : 'Records by Platform'}
                data={chartData.byPlatform}
                dataKey="value"
                colorIndex={1}
                locale={locale}
                defaultChartType="bar"
              />
            )}

            {/* Records by Date */}
            {chartData.byDate && chartData.byDate.length > 0 && (
              <ChartCard
                title={locale === 'kh' ? 'កំណត់ត្រាតាមកាលបរិច្ឆេទ' : 'Records by Date'}
                data={chartData.byDate}
                dataKey="value"
                colorIndex={2}
                angle={-45}
                locale={locale}
                defaultChartType="bar"
              />
            )}

            {/* Records by Acknowledge Status */}
            {chartData.byAcknowledge && (
              <ChartCard
                title={locale === 'kh' ? 'កំណត់ត្រាតាមស្ថានភាព' : 'Records by Acknowledge Status'}
                data={chartData.byAcknowledge}
                dataKey="value"
                colorIndex={3}
                locale={locale}
                defaultChartType="bar"
              />
            )}
          </div>
        </div>
      )}

      {/* Data Table */}
      <Card className="border-primary/20 shadow-xl shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-2xl hover:shadow-primary/10">
        <CardHeader>
          <div className="flex items-center gap-3">
            <div className="w-1.5 h-8 bg-gradient-custom rounded-full"></div>
            <div>
              <CardTitle className="text-xl font-bold">{t(locale, 'admin.common.patientData')}</CardTitle>
              <CardDescription className="mt-1">
            {displayData.length > 0 
              ? t(locale, 'admin.common.showingRecords').replace('{count}', displayData.length.toString()) + (filteredData.length > 0 ? t(locale, 'admin.common.filtered') : '')
              : t(locale, 'admin.common.noDataAvailable')}
          </CardDescription>
            </div>
          </div>
        </CardHeader>
        <CardContent className="p-6">
          {loading ? (
            <div className="space-y-3">
              <Skeleton className="h-12 w-full bg-primary/10" />
              <Skeleton className="h-12 w-full bg-primary/10" />
              <Skeleton className="h-12 w-full bg-primary/10" />
              <Skeleton className="h-12 w-full bg-primary/10" />
            </div>
          ) : !isFiltered ? (
            <div className="py-16 text-center">
              <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
                <FaInfoCircle className="h-8 w-8 text-primary/60" />
              </div>
                <h3 className="text-lg font-semibold mb-2">{t(locale, 'admin.common.noFiltersApplied')}</h3>
              <p className="text-sm text-muted-foreground max-w-md mx-auto font-medium">
                  {t(locale, 'admin.common.selectDateRangeApplyFilters')}
                </p>
            </div>
          ) : displayData.length === 0 ? (
            <EmptyState
              title={t(locale, 'admin.common.noDataFound')}
              description={t(locale, 'admin.common.noRecordsFoundDateRange').replace('{startDate}', startDate).replace('{endDate}', endDate)}
            />
          ) : (
            <div className="rounded-md border overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>ACKNOWLEDGE</TableHead>
                    <TableHead>START</TableHead>
                    <TableHead>Site</TableHead>
                    <TableHead>Q1A</TableHead>
                    <TableHead>Q2A</TableHead>
                    <TableHead>Q3A</TableHead>
                    <TableHead>Q4A</TableHead>
                    <TableHead>Q5A</TableHead>
                    <TableHead>Q6A</TableHead>
                    <TableHead>Q7A</TableHead>
                    <TableHead>Q8A</TableHead>
                    <TableHead>Q9A</TableHead>
                    <TableHead>Q10A</TableHead>
                    <TableHead>Q1B</TableHead>
                    <TableHead>Q2B</TableHead>
                    <TableHead>Q3B</TableHead>
                    <TableHead>Q4B</TableHead>
                    <TableHead>Q5B</TableHead>
                    <TableHead>Q1C</TableHead>
                    <TableHead>Q2C</TableHead>
                    <TableHead>Q3C_1</TableHead>
                    <TableHead>Q3C_2</TableHead>
                    <TableHead>Q3C_3</TableHead>
                    <TableHead>Q3C_4</TableHead>
                    <TableHead>Q3C_5</TableHead>
                    <TableHead>Q3C_6</TableHead>
                    <TableHead>Q3C_7</TableHead>
                    <TableHead>Q3C_8</TableHead>
                    <TableHead>Q4C</TableHead>
                    <TableHead>Q5C1</TableHead>
                    <TableHead>Q5C2</TableHead>
                    <TableHead>Q5C3</TableHead>
                    <TableHead>Q6C_1</TableHead>
                    <TableHead>Q6C_2</TableHead>
                    <TableHead>Q6C_3</TableHead>
                    <TableHead>Q6C_4</TableHead>
                    <TableHead>Q6C_5</TableHead>
                    <TableHead>Q6C_6</TableHead>
                    <TableHead>Q6C_7</TableHead>
                    <TableHead>Q6C_8</TableHead>
                    <TableHead>Q7C</TableHead>
                    <TableHead>Q8C</TableHead>
                    <TableHead>Q9C_1</TableHead>
                    <TableHead>Q9C_2</TableHead>
                    <TableHead>Q9C_3</TableHead>
                    <TableHead>Q9C_4</TableHead>
                    <TableHead>Q9C_5</TableHead>
                    <TableHead>Q10C</TableHead>
                    <TableHead>Q11C</TableHead>
                    <TableHead>Q12C</TableHead>
                    <TableHead>Q13C</TableHead>
                    <TableHead>Q14C</TableHead>
                    <TableHead>Platform</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {displayData.slice(0, 100).map((row, idx) => (
                    <TableRow key={idx} className="hover:bg-muted/50">
                      <TableCell>
                        {(() => {
                          // Get ACKNOWLEDGE value from any case variation (explicitly check for undefined, not using ??)
                          const acknowledge = row.ACKNOWLEDGE !== undefined ? row.ACKNOWLEDGE : 
                                             (row.acknowledge !== undefined ? row.acknowledge : 
                                             (row.Acknowledge !== undefined ? row.Acknowledge : null));
                          
                          // ACKNOWLEDGE: 1 = agree (consented), 0 = disagree (didn't consent)
                          // Explicitly check for 1 - handle null/undefined before string conversion
                          let isAgree = false;
                          if (acknowledge !== null && acknowledge !== undefined) {
                            isAgree = acknowledge === 1 || acknowledge === '1' || String(acknowledge).trim() === '1';
                          }
                          // If acknowledge is null, undefined, 0, or anything other than 1, isAgree remains false
                          
                          // Debug log for first few rows
                          if (idx < 3) {
                            console.log('[Patient Frontend] ACKNOWLEDGE check:', {
                              rowACKNOWLEDGE: row.ACKNOWLEDGE,
                              rowacknowledge: row.acknowledge,
                              rowAcknowledge: row.Acknowledge,
                              acknowledge,
                              isAgree,
                              type: typeof acknowledge,
                              value: acknowledge
                            });
                          }
                          
                          return (
                            <Badge variant={isAgree ? "default" : "secondary"}>
                              {isAgree ? t(locale, 'admin.common.agree') : t(locale, 'admin.common.disagree')}
                            </Badge>
                          );
                        })()}
                      </TableCell>
                      <TableCell>{row.START || row.start || '-'}</TableCell>
                      <TableCell>{row.site || row.sitename || '-'}</TableCell>
                      <TableCell>{row.Q1A ?? '-'}</TableCell>
                      <TableCell>{row.Q2A ?? '-'}</TableCell>
                      <TableCell>{row.Q3A ?? '-'}</TableCell>
                      <TableCell>{row.Q4A ?? '-'}</TableCell>
                      <TableCell>{row.Q5A ?? '-'}</TableCell>
                      <TableCell>{row.Q6A ?? '-'}</TableCell>
                      <TableCell>{row.Q7A ?? '-'}</TableCell>
                      <TableCell>{row.Q8A ?? '-'}</TableCell>
                      <TableCell>{row.Q9A ?? '-'}</TableCell>
                      <TableCell>{row.Q10A ?? '-'}</TableCell>
                      <TableCell>{row.Q1B ?? '-'}</TableCell>
                      <TableCell>{row.Q2B ?? '-'}</TableCell>
                      <TableCell>{row.Q3B ?? '-'}</TableCell>
                      <TableCell>{row.Q4B ?? '-'}</TableCell>
                      <TableCell>{row.Q5B ?? '-'}</TableCell>
                      <TableCell>{row.Q1C ?? '-'}</TableCell>
                      <TableCell>{row.Q2C ?? '-'}</TableCell>
                      <TableCell>{row.Q3C_1 ?? '-'}</TableCell>
                      <TableCell>{row.Q3C_2 ?? '-'}</TableCell>
                      <TableCell>{row.Q3C_3 ?? '-'}</TableCell>
                      <TableCell>{row.Q3C_4 ?? '-'}</TableCell>
                      <TableCell>{row.Q3C_5 ?? '-'}</TableCell>
                      <TableCell>{row.Q3C_6 ?? '-'}</TableCell>
                      <TableCell>{row.Q3C_7 ?? '-'}</TableCell>
                      <TableCell>{row.Q3C_8 ?? '-'}</TableCell>
                      <TableCell>{row.Q4C ?? '-'}</TableCell>
                      <TableCell>{row.Q5C1 ?? '-'}</TableCell>
                      <TableCell>{row.Q5C2 ?? '-'}</TableCell>
                      <TableCell>{row.Q5C3 ?? '-'}</TableCell>
                      <TableCell>{row.Q6C_1 ?? '-'}</TableCell>
                      <TableCell>{row.Q6C_2 ?? '-'}</TableCell>
                      <TableCell>{row.Q6C_3 ?? '-'}</TableCell>
                      <TableCell>{row.Q6C_4 ?? '-'}</TableCell>
                      <TableCell>{row.Q6C_5 ?? '-'}</TableCell>
                      <TableCell>{row.Q6C_6 ?? '-'}</TableCell>
                      <TableCell>{row.Q6C_7 ?? '-'}</TableCell>
                      <TableCell>{row.Q6C_8 ?? '-'}</TableCell>
                      <TableCell>{row.Q7C ?? '-'}</TableCell>
                      <TableCell>{row.Q8C ?? '-'}</TableCell>
                      <TableCell>{row.Q9C_1 ?? '-'}</TableCell>
                      <TableCell>{row.Q9C_2 ?? '-'}</TableCell>
                      <TableCell>{row.Q9C_3 ?? '-'}</TableCell>
                      <TableCell>{row.Q9C_4 ?? '-'}</TableCell>
                      <TableCell>{row.Q9C_5 ?? '-'}</TableCell>
                      <TableCell>{row.Q10C ?? '-'}</TableCell>
                      <TableCell>{row.Q11C ?? '-'}</TableCell>
                      <TableCell>{row.Q12C ?? '-'}</TableCell>
                      <TableCell>{row.Q13C ?? '-'}</TableCell>
                      <TableCell>{row.Q14C ?? '-'}</TableCell>
                      <TableCell>
                        <Badge variant="outline">
                          {row.META_INSTANCE_ID ? 'ODK' : 'Online'}
                        </Badge>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
              {displayData.length > 100 && (
                <div className="p-4 text-center text-sm text-muted-foreground">
                  {t(locale, 'admin.common.showingFirstRecords').replace('{count}', '100').replace('{total}', displayData.length.toString())}
                </div>
              )}
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
