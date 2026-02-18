import { useState } from 'react';
import { Card, CardContent, CardTitle } from '../ui/card';
import { Badge } from '../ui/badge';
import { ChartContainer, ChartTooltip, ChartTooltipContent } from '../ui/chart';
import { Bar, BarChart, Line, LineChart, Pie, PieChart, Area, AreaChart, Cell, CartesianGrid, XAxis, YAxis, Legend, ComposedChart } from 'recharts';
import { PiChartBarFill, PiChartLineUpFill, PiChartPieFill, PiChartLineUp, PiCaretDown, PiArrowsOut, PiX, PiCaretLeft, PiCaretRight } from 'react-icons/pi';

const CHART_COLORS = [
  'hsl(var(--chart-1))',
  'hsl(var(--chart-2))',
  'hsl(var(--chart-3))',
  'hsl(var(--chart-4))',
  'hsl(var(--chart-5))',
];

const CHART_TYPES = [
  { value: 'bar', label: 'Bar', icon: PiChartBarFill },
  { value: 'line', label: 'Line', icon: PiChartLineUpFill },
  { value: 'pie', label: 'Pie', icon: PiChartPieFill },
  { value: 'area', label: 'Area', icon: PiChartLineUp },
];

export default function ChartCard({ 
  title, 
  data, 
  dataKey = 'value', 
  nameKey = 'name',
  colorIndex = 0,
  height = 300,
  showGrid = true,
  angle = 0,
  domain,
  locale = 'en',
  defaultChartType = 'bar',
  formatter,
  /** When set, render grouped bars (one per item); each item: { dataKey, label? }. Uses nameKey for X axis. */
  bars = null
}) {
  const [chartType, setChartType] = useState(defaultChartType);
  const [menuOpen, setMenuOpen] = useState(false);
  const [isFullscreen, setIsFullscreen] = useState(false);

  if (!data || data.length === 0) return null;

  const color = CHART_COLORS[colorIndex % CHART_COLORS.length];
  const isMultiBar = bars && Array.isArray(bars) && bars.length > 0;
  const chartConfig = isMultiBar
    ? Object.fromEntries(bars.map((b, idx) => [b.dataKey, { label: b.label ?? b.dataKey, color: CHART_COLORS[(colorIndex + idx) % CHART_COLORS.length] }]))
    : { [dataKey]: { label: dataKey, color } };

  const renderChart = (isFullscreenMode = false) => {
    switch (chartType) {
      case 'bar':
        return (
          <BarChart data={data}>
            {showGrid && <CartesianGrid vertical={false} />}
            <XAxis 
              dataKey={nameKey} 
              tickLine={false} 
              tickMargin={10} 
              axisLine={false}
              angle={angle}
              textAnchor="middle"
            />
            <YAxis 
              tickLine={false} 
              tickMargin={10} 
              axisLine={false}
              domain={domain}
            />
            <ChartTooltip content={<ChartTooltipContent />} formatter={formatter} />
            {isMultiBar ? (
              <>
                {bars.map((b, idx) => (
                  <Bar 
                    key={b.dataKey} 
                    dataKey={b.dataKey} 
                    name={b.label ?? b.dataKey} 
                    fill={CHART_COLORS[(colorIndex + idx) % CHART_COLORS.length]} 
                    radius={8} 
                  />
                ))}
                <Legend
                  content={({ payload }) => (
                    <div className="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 pt-2">
                      {payload && payload.length > 0 && (
                        <div className="flex flex-wrap items-center gap-3">
                          {payload.map((entry) => (
                            <span key={entry.value} className="inline-flex items-center gap-1.5 text-xs text-muted-foreground">
                              <span
                                className="inline-block w-3 h-3 rounded-sm flex-shrink-0"
                                style={{ backgroundColor: entry.color }}
                              />
                              <span>{entry.value}</span>
                            </span>
                          ))}
                        </div>
                      )}
                    </div>
                  )}
                />
              </>
            ) : (
              <Bar dataKey={dataKey} fill={color} radius={8} />
            )}
          </BarChart>
        );

      case 'line':
        return (
          <LineChart data={data}>
            {showGrid && <CartesianGrid vertical={false} strokeDasharray="3 3" />}
            <XAxis 
              dataKey={nameKey} 
              tickLine={false} 
              tickMargin={10} 
              axisLine={false}
              angle={0}
              textAnchor="middle"
            />
            <YAxis 
              tickLine={false} 
              tickMargin={10} 
              axisLine={false}
              domain={domain}
            />
            <ChartTooltip cursor={{ strokeDasharray: '3 3' }} content={<ChartTooltipContent />} formatter={formatter} />
            <Line 
              type="monotone" 
              dataKey={dataKey} 
              stroke={color} 
              strokeWidth={2}
              dot={{ fill: color, r: 4 }}
              activeDot={{ r: 6 }}
            />
          </LineChart>
        );

      case 'pie':
        // Calculate dynamic radius based on fullscreen mode
        const pieRadius = isFullscreenMode ? '40%' : 80;
        const innerRadius = isFullscreenMode ? '20%' : 0;
        
        return (
          <PieChart>
            <Pie
              data={data}
              cx="50%"
              cy={isFullscreenMode ? "45%" : "50%"}
              labelLine={true}
              label={({ [nameKey]: name, percent }) => {
                const percentage = (percent * 100).toFixed(0);
                // Show label only if percentage is significant (> 1%) to prevent overlap
                if (percent < 0.01) return '';
                // Truncate long names - longer in fullscreen
                const maxLength = isFullscreenMode ? 30 : 20;
                const truncatedName = name.length > maxLength ? name.substring(0, maxLength) + '...' : name;
                return `${truncatedName}: ${percentage}%`;
              }}
              outerRadius={pieRadius}
              innerRadius={innerRadius}
              fill="#8884d8"
              dataKey={dataKey}
              paddingAngle={0}
            >
              {data.map((entry, index) => (
                <Cell key={`cell-${index}`} fill={CHART_COLORS[index % CHART_COLORS.length]} />
              ))}
            </Pie>
            <ChartTooltip content={<ChartTooltipContent />} formatter={formatter} />
            <Legend 
              wrapperStyle={{ paddingTop: isFullscreenMode ? '30px' : '10px' }}
              iconSize={isFullscreenMode ? 16 : 10}
              iconType="circle"
              fontSize={isFullscreenMode ? 14 : 12}
              formatter={(value) => {
                const item = data.find(d => d[nameKey] === value);
                if (!item) return value;
                const total = data.reduce((sum, d) => sum + (d[dataKey] || 0), 0);
                const percentage = total > 0 ? ((item[dataKey] / total) * 100).toFixed(0) : '0';
                return `${value} (${percentage}%)`;
              }}
            />
          </PieChart>
        );

      case 'area':
        return (
          <AreaChart data={data}>
            {showGrid && <CartesianGrid vertical={false} strokeDasharray="3 3" />}
            <XAxis 
              dataKey={nameKey} 
              tickLine={false} 
              tickMargin={10} 
              axisLine={false}
              angle={0}
              textAnchor="middle"
            />
            <YAxis 
              tickLine={false} 
              tickMargin={10} 
              axisLine={false}
              domain={domain}
            />
            <ChartTooltip cursor={{ strokeDasharray: '3 3' }} content={<ChartTooltipContent />} formatter={formatter} />
            <Area 
              type="monotone" 
              dataKey={dataKey} 
              stroke={color}
              fill={color}
              fillOpacity={0.3}
            />
          </AreaChart>
        );

      case 'all':
        // Show all chart types in a composed chart (bar + line + area)
        return (
          <ComposedChart data={data}>
            {showGrid && <CartesianGrid vertical={false} strokeDasharray="3 3" />}
            <XAxis 
              dataKey={nameKey} 
              tickLine={false} 
              tickMargin={10} 
              axisLine={false}
              angle={0}
              textAnchor="middle"
            />
            <YAxis 
              tickLine={false} 
              tickMargin={10} 
              axisLine={false}
              domain={domain}
            />
            <ChartTooltip content={<ChartTooltipContent />} formatter={formatter} />
            <Legend />
            <Bar dataKey={dataKey} fill={CHART_COLORS[0]} radius={8} />
            <Line 
              type="monotone" 
              dataKey={dataKey} 
              stroke={CHART_COLORS[1]} 
              strokeWidth={2}
              dot={{ fill: CHART_COLORS[1], r: 4 }}
              activeDot={{ r: 6 }}
            />
            <Area 
              type="monotone" 
              dataKey={dataKey} 
              stroke={CHART_COLORS[2]}
              fill={CHART_COLORS[2]}
              fillOpacity={0.2}
            />
          </ComposedChart>
        );

      default:
        return null;
    }
  };

  const chartTypeConfig = CHART_TYPES.find(t => t.value === chartType);
  const ChartIcon = chartTypeConfig?.icon || PiChartBarFill;

  const currentChartType = CHART_TYPES.find(t => t.value === chartType);
  const CurrentIcon = currentChartType?.icon || PiChartBarFill;

  return (
    <>
      <div className="group">
        <div className="flex items-center gap-3 mb-4">
          <div className="w-1.5 h-8 bg-gradient-custom rounded-full"></div>
          <CardTitle className="text-xl font-bold text-muted-foreground flex-1">{title}</CardTitle>
          {/* Fullscreen Button */}
          <button
            onClick={() => setIsFullscreen(true)}
            className="p-1.5 rounded-md hover:bg-primary/10 text-muted-foreground hover:text-primary transition-colors"
            title="View fullscreen"
          >
            <PiArrowsOut className="h-4 w-4" />
          </button>
          {/* Chart Type Selector Badge */}
          <div className="relative">
            <Badge 
              variant="secondary" 
              className="bg-primary/10 text-primary border-primary/20 flex items-center gap-1.5 px-2.5 py-1 cursor-pointer hover:bg-primary/20 transition-colors"
              onClick={() => setMenuOpen(!menuOpen)}
            >
              <CurrentIcon className="h-3.5 w-3.5" />
              <span className="text-xs font-semibold">{currentChartType?.label || 'Bar'}</span>
              <PiCaretDown className={`h-3 w-3 opacity-70 transition-transform ${menuOpen ? 'rotate-180' : ''}`} />
            </Badge>
            {/* Dropdown Menu */}
            {menuOpen && (
              <>
                <div 
                  className="fixed inset-0 z-40" 
                  onClick={() => setMenuOpen(false)}
                />
                <div className="absolute top-full right-0 mt-1 z-50 bg-card border border-primary/20 rounded-lg shadow-xl backdrop-blur-sm min-w-[140px] py-1">
                  {CHART_TYPES.map((type) => {
                    const Icon = type.icon;
                    const isSelected = type.value === chartType;
                    return (
                      <button
                        key={type.value}
                        onClick={() => {
                          setChartType(type.value);
                          setMenuOpen(false);
                        }}
                        className={`w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-primary/10 transition-colors ${
                          isSelected ? 'bg-primary/5' : ''
                        }`}
                      >
                        <Icon className="h-4 w-4 text-primary" />
                        <span className="font-medium text-muted-foreground">{type.label}</span>
                      </button>
                    );
                  })}
                </div>
              </>
            )}
          </div>
        </div>
        <Card className="border-primary/20 shadow-xl shadow-primary/5 backdrop-blur-sm bg-card/95 transition-all duration-300 hover:shadow-2xl hover:shadow-primary/10 hover:border-primary/30 hover:-translate-y-1">
          <CardContent className="p-6">
            <ChartContainer
              config={chartConfig}
              className="h-[300px]"
              style={{ height: `${height}px` }}
            >
              {renderChart(false)}
            </ChartContainer>
          </CardContent>
        </Card>
      </div>

      {/* Fullscreen Modal */}
      {isFullscreen && (
        <div 
          className="fixed inset-0 z-[100] bg-background/95 backdrop-blur-sm flex flex-col"
          onClick={() => setIsFullscreen(false)}
        >
          {/* Header */}
          <div className="flex items-center justify-between p-6 border-b border-primary/20">
            <div className="flex items-center gap-3 flex-1">
              <div className="w-1.5 h-8 bg-gradient-custom rounded-full"></div>
              <CardTitle className="text-2xl font-bold text-muted-foreground">{title}</CardTitle>
            </div>
            
            {/* Chart Type Selector in Fullscreen */}
            <div className="flex items-center gap-3">
              <div className="relative">
                <Badge 
                  variant="secondary" 
                  className="bg-primary/10 text-primary border-primary/20 flex items-center gap-1.5 px-3 py-1.5 cursor-pointer hover:bg-primary/20 transition-colors"
                  onClick={(e) => {
                    e.stopPropagation();
                    setMenuOpen(!menuOpen);
                  }}
                >
                  <CurrentIcon className="h-4 w-4" />
                  <span className="text-sm font-semibold">{currentChartType?.label || 'Bar'}</span>
                  <PiCaretDown className={`h-3.5 w-3.5 opacity-70 transition-transform ${menuOpen ? 'rotate-180' : ''}`} />
                </Badge>
                {/* Dropdown Menu */}
                {menuOpen && (
                  <>
                    <div 
                      className="fixed inset-0 z-40" 
                      onClick={(e) => {
                        e.stopPropagation();
                        setMenuOpen(false);
                      }}
                    />
                    <div className="absolute top-full right-0 mt-1 z-50 bg-card border border-primary/20 rounded-lg shadow-xl backdrop-blur-sm min-w-[160px] py-1">
                      {CHART_TYPES.map((type) => {
                        const Icon = type.icon;
                        const isSelected = type.value === chartType;
                        return (
                          <button
                            key={type.value}
                            onClick={(e) => {
                              e.stopPropagation();
                              setChartType(type.value);
                              setMenuOpen(false);
                            }}
                            className={`w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-primary/10 transition-colors ${
                              isSelected ? 'bg-primary/5' : ''
                            }`}
                          >
                            <Icon className="h-4 w-4 text-primary" />
                            <span className="font-medium text-muted-foreground">{type.label}</span>
                          </button>
                        );
                      })}
                    </div>
                  </>
                )}
              </div>
              
              {/* Navigation Buttons */}
              <div className="flex items-center gap-2">
                <button
                  onClick={(e) => {
                    e.stopPropagation();
                    const currentIndex = CHART_TYPES.findIndex(t => t.value === chartType);
                    const prevIndex = currentIndex > 0 ? currentIndex - 1 : CHART_TYPES.length - 1;
                    setChartType(CHART_TYPES[prevIndex].value);
                  }}
                  className="p-2 rounded-md hover:bg-primary/10 text-muted-foreground hover:text-primary transition-colors"
                  title="Previous chart type"
                >
                  <PiCaretLeft className="h-5 w-5" />
                </button>
                <button
                  onClick={(e) => {
                    e.stopPropagation();
                    const currentIndex = CHART_TYPES.findIndex(t => t.value === chartType);
                    const nextIndex = currentIndex < CHART_TYPES.length - 1 ? currentIndex + 1 : 0;
                    setChartType(CHART_TYPES[nextIndex].value);
                  }}
                  className="p-2 rounded-md hover:bg-primary/10 text-muted-foreground hover:text-primary transition-colors"
                  title="Next chart type"
                >
                  <PiCaretRight className="h-5 w-5" />
                </button>
              </div>
              
              <button
                onClick={(e) => {
                  e.stopPropagation();
                  setIsFullscreen(false);
                }}
                className="p-2 rounded-md hover:bg-primary/10 text-muted-foreground hover:text-primary transition-colors"
                title="Close fullscreen"
              >
                <PiX className="h-5 w-5" />
              </button>
            </div>
          </div>

          {/* Fullscreen Chart */}
          <div 
            className="flex-1 flex items-center justify-center p-6 overflow-hidden"
            onClick={(e) => e.stopPropagation()}
          >
            <Card className="border-primary/20 shadow-xl shadow-primary/5 backdrop-blur-sm bg-card/95 w-full h-full">
              <CardContent className="p-8 h-full flex items-center justify-center">
                <ChartContainer
                  config={chartConfig}
                  className="w-full h-full"
                  style={{ 
                    height: 'calc(100vh - 180px)', 
                    minHeight: '700px',
                    width: '100%'
                  }}
                >
                  {renderChart(true)}
                </ChartContainer>
              </CardContent>
            </Card>
          </div>
        </div>
      )}
    </>
  );
}

