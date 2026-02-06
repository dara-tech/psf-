import * as React from "react"
import * as RechartsPrimitive from "recharts"
import { cn } from "../../lib/utils"

const THEMES = { light: "", dark: ".dark" }

const ChartContainer = React.forwardRef(
  ({ id, config, className, children, ...props }, ref) => {
    const uniqueId = React.useId()
    const chartId = `chart-${id || uniqueId.replace(/:/g, "")}`

    return (
      <div
        data-chart={chartId}
        className={cn(
          "w-full text-xs [&_.recharts-cartesian-axis-tick_text]:fill-muted-foreground [&_.recharts-cartesian-grid_line[stroke='#ccc']]:stroke-border/50 [&_.recharts-curve.recharts-tooltip-cursor]:stroke-border [&_.recharts-dot[stroke='#fff']]:stroke-transparent [&_.recharts-layer]:outline-none [&_.recharts-polar-grid_[stroke='#ccc']]:stroke-border [&_.recharts-radial-bar-background-sector]:fill-muted [&_.recharts-rectangle.recharts-tooltip-cursor]:fill-muted [&_.recharts-reference-line-line]:stroke-border [&_.recharts-sector[stroke='#fff']]:stroke-transparent [&_.recharts-sector]:outline-none [&_.recharts-surface]:outline-none",
          className
        )}
        {...props}
        ref={ref}
      >
        <ChartStyle id={chartId} config={config} />
        <RechartsPrimitive.ResponsiveContainer width="100%" height="100%">
          {children}
        </RechartsPrimitive.ResponsiveContainer>
      </div>
    )
  }
)
ChartContainer.displayName = "Chart"

const ChartStyle = ({ id, config }) => {
  const colorConfig = Object.entries(config ?? {}).reduce(
    (acc, [key, value]) => {
      acc[`--color-${key}`] = value
      return acc
    },
    {}
  )

  return (
    <React.Fragment>
      <style
        dangerouslySetInnerHTML={{
          __html: Object.entries(THEMES)
            .map(
              ([theme, prefix]) => `
${prefix} [data-chart=${id}] {
  ${Object.entries(colorConfig)
    .map(([key, value]) => `${key}: ${value};`)
    .join("\n")}
}
`
            )
            .join("\n"),
        }}
      />
    </React.Fragment>
  )
}

const ChartTooltip = RechartsPrimitive.Tooltip

const ChartTooltipContent = React.forwardRef(
  ({ active, payload, className, indicator = "dot", label, ...props }, ref) => {
    const tooltipLabel = React.useMemo(() => {
      if (label) {
        return label
      }
      if (payload?.length) {
        return payload[0].name
      }
      return null
    }, [label, payload])

    if (!active || !payload?.length) {
      return null
    }

    // Filter out non-DOM props that recharts might pass
    const {
      allowEscapeViewBox,
      animationDuration,
      animationEasing,
      axisId,
      contentStyle,
      filterNull,
      includeHidden,
      isAnimationActive,
      itemSorter,
      itemStyle,
      labelStyle,
      reverseDirection,
      useTranslate3d,
      wrapperStyle,
      activeIndex,
      accessibilityLayer,
      cursor,
      formatter,
      ...domProps
    } = props;

    return (
      <div
        ref={ref}
        className={cn(
          "rounded-lg border bg-background px-2.5 py-1.5 text-sm shadow-md",
          className
        )}
        {...domProps}
      >
        <div className="grid gap-1.5">
          {tooltipLabel && (
            <div className="font-medium leading-none tracking-tight">
              {tooltipLabel}
            </div>
          )}
          <div className="grid gap-1.5">
            {payload.map((item, index) => {
              const key = `${item.dataKey}-${index}`
              const itemConfig = item.payload?.payload?.config?.[item.dataKey]
              const indicatorColor = item.payload?.fill || item.color

              return (
                <div
                  key={key}
                  className="flex w-full flex-wrap items-stretch gap-2 [&>svg]:h-2.5 [&>svg]:w-2.5 [&>svg]:text-muted-foreground"
                >
                  {indicator === "dot" && (
                    <div
                      className="shrink-0 rounded-[2px] border-[--color-border] bg-[--color-bg]"
                      style={{
                        "--color-border": indicatorColor,
                        "--color-bg": indicatorColor,
                      }}
                    />
                  )}
                  {indicator === "line" && (
                    <div
                      className="shrink-0 border-[2px] border-[--color-border]"
                      style={{
                        "--color-border": indicatorColor,
                      }}
                    />
                  )}
                  <div
                    className={cn(
                      "flex flex-1 items-center gap-2 leading-none",
                      indicator === "dot" && "justify-between"
                    )}
                  >
                    <div className="grid gap-1.5">
                      <span className="text-muted-foreground">
                        {itemConfig?.label || item.name}
                      </span>
                      {itemConfig?.label && (
                        <span className="font-medium tabular-nums text-foreground">
                          {typeof item.value === "number"
                            ? item.value.toLocaleString()
                            : item.value}
                        </span>
                      )}
                    </div>
                    {!itemConfig?.label && (
                      <span className="font-medium tabular-nums text-foreground">
                        {typeof item.value === "number"
                          ? item.value.toLocaleString()
                          : item.value}
                      </span>
                    )}
                  </div>
                </div>
              )
            })}
          </div>
        </div>
      </div>
    )
  }
)
ChartTooltipContent.displayName = "ChartTooltipContent"

const ChartLegend = RechartsPrimitive.Legend

const ChartLegendContent = React.forwardRef(
  (
    {
      className,
      hideIcon = false,
      payload,
      ...props
    },
    ref
  ) => {
    if (!payload?.length) {
      return null
    }

    return (
      <div
        ref={ref}
        className={cn(
          "flex items-center justify-center gap-4",
          className
        )}
        {...props}
      >
        {payload.map((item) => {
          const key = `${item.dataKey}-${item.value}`
          const itemConfig = item.payload?.config?.[item.dataKey]

          return (
            <div
              key={key}
              className={cn(
                "flex items-center gap-1.5 [&>svg]:h-3 [&>svg]:w-3 [&>svg]:text-muted-foreground"
              )}
            >
              {!hideIcon && (
                <div
                  className="h-2 w-2 shrink-0 rounded-[2px]"
                  style={{
                    backgroundColor: item.color,
                  }}
                />
              )}
              {itemConfig?.label || item.value}
            </div>
          )
        })}
      </div>
    )
  }
)
ChartLegendContent.displayName = "ChartLegendContent"

export {
  ChartContainer,
  ChartTooltip,
  ChartTooltipContent,
  ChartLegend,
  ChartLegendContent,
}

