import { useEffect, useState } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '../components/ui/table';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { Skeleton } from '../components/ui/skeleton';
import { Badge } from '../components/ui/badge';
import EmptyState from '../components/EmptyState';
import { FaFileExport, FaFilter, FaCalendarAlt, FaSpinner, FaHospital } from 'react-icons/fa';
import api from '../lib/api';
import { useUIStore } from '../lib/stores/uiStore';
import { t } from '../lib/translations/index';

export default function HFS() {
  const { locale } = useUIStore();
  const [sites, setSites] = useState([]);
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');
  const [selectedSites, setSelectedSites] = useState(['*']);
  const [filteredData, setFilteredData] = useState([]);
  const [isFiltered, setIsFiltered] = useState(false);
  const [loading, setLoading] = useState(false);
  const [filterLoading, setFilterLoading] = useState(false);
  const [filterError, setFilterError] = useState('');

  useEffect(() => {
    // Initialize default dates (last 3 months for HFS)
    const today = new Date();
    const lastQuarter = new Date();
    lastQuarter.setMonth(today.getMonth() - 3);
    setEndDate(today.toISOString().split('T')[0]);
    setStartDate(lastQuarter.toISOString().split('T')[0]);

    // Fetch sites list (and confirm auth)
    const init = async () => {
      setLoading(true);
      try {
        const response = await api.post('/reporting/hfs/table', {
          // no dates -> backend returns empty data + sites
          locale: locale || 'en'
        });
        if (response.data?.sites) {
          setSites(response.data.sites || []);
        }
      } catch (error) {
        console.error('Error initializing HFS export:', error);
      } finally {
        setLoading(false);
      }
    };

    init();
  }, [locale]);

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
      const response = await api.post('/reporting/hfs/table', {
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
      console.error('HFS filter error:', error);
      setFilterError(error.response?.data?.error || error.message || t(locale, 'admin.common.failedToFilterData'));
    } finally {
      setFilterLoading(false);
    }
  };

  const handleExport = () => {
    if (!isFiltered || filteredData.length === 0) {
      alert(t(locale, 'admin.common.noDataToExport'));
      return;
    }

    const headers = Object.keys(filteredData[0]);
    const csvContent = [
      headers.join(','),
      ...filteredData.map(row =>
        headers.map(header => {
          const value = row[header] ?? '';
          return `"${String(value).replace(/"/g, '""')}"`;
        }).join(',')
      )
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `hfs-report-${startDate}-to-${endDate}.csv`;
    link.click();
  };

  const displayData = isFiltered ? filteredData : [];

  return (
    <div className="min-h-screen">
      {/* Header */}
      <div className="mb-8 relative">
        <div className="absolute inset-0 bg-gradient-to-r from-primary/10 via-primary/5 to-transparent rounded-2xl blur-3xl -z-10"></div>
        <div className="relative flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
          <div className="flex items-start gap-3 flex-1">
            <div className="w-1 bg-gradient-to-b from-primary to-primary/60 rounded-full flex-shrink-0" style={{ minHeight: '3.5rem' }}></div>
            <div className="flex-1 min-w-0">
              <h1 className="text-4xl font-bold tracking-tight text-foreground break-words leading-tight">
                {t(locale, 'admin.hfs.export')}
              </h1>
              <p className="text-muted-foreground mt-1.5 text-lg break-words">
                {t(locale, 'admin.hfs.exportDescription')}
              </p>
            </div>
          </div>
          <div className="flex items-center gap-2 sm:ml-4">
            <Button
              onClick={handleExport}
              className="gap-2 bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/30 transition-all duration-300 text-white font-semibold px-6 py-2.5 rounded-lg"
              disabled={displayData.length === 0 || filterLoading}
            >
              <FaFileExport className="h-4 w-4" />
              {t(locale, 'admin.common.exportCSV')}
            </Button>
          </div>
        </div>
      </div>

      {/* Filter Card */}
      <Card className="border-primary/20 shadow-xl shadow-primary/5 backdrop-blur-sm bg-card/95 mb-8 transition-all duration-300 hover:shadow-2xl hover:shadow-primary/10">
        <CardHeader className="pb-4">
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
              <FaCalendarAlt className="h-4 w-4 text-primary" />
            </div>
            <CardTitle className="text-xl">{t(locale, 'admin.common.filters')}</CardTitle>
          </div>
          <CardDescription className="mt-1">
            {t(locale, 'admin.common.selectDateRangeSites')}
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div className="flex flex-wrap items-end gap-4">
            <div className="flex-1 min-w-[180px] space-y-2">
              <Label htmlFor="startdate" className="text-sm font-semibold text-foreground">
                {t(locale, 'admin.common.startDate')}
              </Label>
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
              <Label htmlFor="enddate" className="text-sm font-semibold text-foreground">
                {t(locale, 'admin.common.endDate')}
              </Label>
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
              <Label htmlFor="sites" className="text-sm font-semibold text-foreground">
                {t(locale, 'admin.common.sites')}
              </Label>
              <Select
                value={selectedSites.length > 0 ? selectedSites[0] : '*'}
                onValueChange={(value) => {
                  setSelectedSites(value === '*' ? ['*'] : [value]);
                  setFilterError('');
                  setIsFiltered(false);
                }}
                disabled={filterLoading}
              >
                <SelectTrigger className="w-full border-primary/20 hover:border-primary/40 focus:ring-primary/20 transition-all duration-200 bg-background/50 backdrop-blur-sm">
                  <SelectValue placeholder={t(locale, 'admin.common.selectSites')} />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="*">{t(locale, 'admin.common.allSites')}</SelectItem>
                  {sites.map((site) => (
                    <SelectItem key={site} value={site}>
                      {site}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="flex items-center gap-3">
              <Button
                onClick={handleFilter}
                disabled={filterLoading}
                className="gap-2 bg-primary text-primary-foreground hover:bg-primary/90"
              >
                {filterLoading ? (
                  <>
                    <FaSpinner className="h-4 w-4 animate-spin" />
                    {t(locale, 'admin.common.loading')}
                  </>
                ) : (
                  <>
                    <FaFilter className="h-4 w-4" />
                    {t(locale, 'admin.common.applyFilters')}
                  </>
                )}
              </Button>
            </div>
          </div>
          {filterError && (
            <p className="text-sm text-destructive mt-3 font-medium">{filterError}</p>
          )}
        </CardContent>
      </Card>

      {/* Table */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <FaHospital className="h-5 w-5" />
            {t(locale, 'admin.hfs.data')}
          </CardTitle>
          <CardDescription>
            {t(locale, 'admin.hfs.exportDescription')}
          </CardDescription>
        </CardHeader>
        <CardContent>
          {loading || filterLoading ? (
            <div className="space-y-3">
              <Skeleton className="h-12 w-full" />
              <Skeleton className="h-12 w-full" />
              <Skeleton className="h-12 w-full" />
            </div>
          ) : !isFiltered ? (
            <EmptyState
              title={t(locale, 'admin.common.noFiltersApplied')}
              description={t(locale, 'admin.common.selectDateRangeApplyFilters')}
            />
          ) : displayData.length === 0 ? (
            <EmptyState
              title={t(locale, 'admin.common.noDataFound')}
              description={t(locale, 'admin.common.noDataForDateRange')}
            />
          ) : (
            <div className="rounded-md border overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>ACKNOWLEDGE</TableHead>
                    <TableHead>START</TableHead>
                    <TableHead>Site</TableHead>
                    <TableHead>DEPT</TableHead>
                    <TableHead>E1</TableHead>
                    <TableHead>E2</TableHead>
                    <TableHead>E3</TableHead>
                    <TableHead>E4</TableHead>
                    <TableHead>E5</TableHead>
                    <TableHead>E6</TableHead>
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
                            console.log('[HFS Frontend] ACKNOWLEDGE check:', {
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
                      <TableCell>
                        <Badge variant="secondary">{row.DEPT ?? '-'}</Badge>
                      </TableCell>
                      <TableCell>{row.E1 ?? '-'}</TableCell>
                      <TableCell>{row.E2 ?? '-'}</TableCell>
                      <TableCell>{row.E3 ?? '-'}</TableCell>
                      <TableCell>{row.E4 ?? '-'}</TableCell>
                      <TableCell>{row.E5 ?? '-'}</TableCell>
                      <TableCell>{row.E6 ?? '-'}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
              {displayData.length > 100 && (
                <p className="text-xs text-muted-foreground mt-2">
                  {t(locale, 'admin.common.showingFirstRecords')
                    .replace('{count}', '100')
                    .replace('{total}', String(displayData.length))}
                </p>
              )}
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
}

