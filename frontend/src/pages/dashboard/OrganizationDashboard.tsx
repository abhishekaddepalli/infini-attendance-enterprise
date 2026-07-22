import React, { useState, useMemo } from 'react';
import { motion } from 'framer-motion';
import { Users, UserCheck, AlertTriangle, CalendarCheck, Globe, Activity } from 'lucide-react';
import { useQuery } from '@tanstack/react-query';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Skeleton } from '@/components/ui/skeleton';
import { AreaChart, Area, BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';
import { attendanceApi } from '@/lib/api';

const NAVY = '#000080'; const SAFFRON = '#FF9933'; const GREEN = '#138808'; const SLATE = '#64748B';

interface StatCardProps {
  title: string; value: number; icon: React.ElementType; color: string;
  loading?: boolean; subtitle?: string; trend?: { value: number; isPositive: boolean };
}

function StatCard({ title, value, icon: Icon, color, loading, subtitle, trend }: StatCardProps) {
  return (
    <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.4 }}>
      <Card className="border-0 shadow-sm hover:shadow-md transition-shadow">
        <CardContent className="p-6">
          <div className="flex items-start justify-between">
            <div className="space-y-2">
              <p className="text-sm font-medium text-slate-500">{title}</p>
              {loading ? <Skeleton className="h-8 w-20" /> : (
                <p className="text-3xl font-bold" style={{ color }}>{value.toLocaleString()}</p>
              )}
              {subtitle && <p className="text-xs text-slate-400">{subtitle}</p>}
              {trend && (
                <p className={`text-xs ${trend.isPositive ? 'text-green-600' : 'text-red-500'}`}>
                  {trend.isPositive ? '↑' : '↓'} {Math.abs(trend.value)}%
                </p>
              )}
            </div>
            <div className="w-12 h-12 rounded-xl flex items-center justify-center" style={{ backgroundColor: `${color}15` }}>
              <Icon className="h-6 w-6" style={{ color }} />
            </div>
          </div>
        </CardContent>
      </Card>
    </motion.div>
  );
}

export default function OrganizationDashboard() {
  const [timeRange, setTimeRange] = useState<'today' | 'week' | 'month'>('today');

  const { data: stats, isLoading } = useQuery({
    queryKey: ['dashboard-stats', timeRange],
    queryFn: () => attendanceApi.getDashboardStats(1, timeRange),
    refetchInterval: 30000,
  });

  const { data: trends } = useQuery({
    queryKey: ['attendance-trends', timeRange],
    queryFn: () => attendanceApi.getAttendanceTrends(1, timeRange),
  });

  return (
    <div className="space-y-6 p-6">
      <header className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-navy-800">Dashboard</h1>
          <p className="text-sm text-slate-500">Real-time workforce overview</p>
        </div>
        <div className="flex items-center gap-3">
          <div className="flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2">
            <span className="relative flex h-2.5 w-2.5">
              <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75" />
              <span className="relative inline-flex h-2.5 w-2.5 rounded-full bg-green-500" />
            </span>
            <span className="text-sm font-medium text-slate-600">{stats?.present ?? 0} Present</span>
          </div>
          <div className="flex rounded-lg border bg-white p-0.5">
            {(['today', 'week', 'month'] as const).map(range => (
              <button key={range} onClick={() => setTimeRange(range)}
                className={`rounded-md px-4 py-1.5 text-sm font-medium transition-colors ${
                  timeRange === range ? 'bg-navy-800 text-white shadow-sm' : 'text-slate-600 hover:text-navy-800'
                }`}>{range.charAt(0).toUpperCase() + range.slice(1)}</button>
            ))}
          </div>
        </div>
      </header>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <StatCard title="Total Employees" value={stats?.totalEmployees ?? 0} icon={Users} color={NAVY} loading={isLoading} />
        <StatCard title="Present Today" value={stats?.present ?? 0} icon={UserCheck} color={GREEN} loading={isLoading} subtitle={`${stats?.attendancePercentage ?? 0}% rate`} />
        <StatCard title="Late Arrivals" value={stats?.late ?? 0} icon={AlertTriangle} color={SAFFRON} loading={isLoading} />
        <StatCard title="On Leave" value={stats?.on_leave ?? 0} icon={CalendarCheck} color={SLATE} loading={isLoading} />
        <StatCard title="Remote" value={stats?.remote ?? 0} icon={Globe} color={NAVY} loading={isLoading} />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <Card className="lg:col-span-2 border-0 shadow-sm">
          <CardHeader className="pb-0"><CardTitle className="text-lg text-navy-800">Attendance Trends</CardTitle></CardHeader>
          <CardContent className="p-6">
            <ResponsiveContainer width="100%" height={300}>
              <AreaChart data={trends}>
                <defs>
                  <linearGradient id="colorPresent" x1="0" y1="0" x2="0" y2="1"><stop offset="5%" stopColor={GREEN} stopOpacity={0.3} /><stop offset="95%" stopColor={GREEN} stopOpacity={0} /></linearGradient>
                  <linearGradient id="colorLate" x1="0" y1="0" x2="0" y2="1"><stop offset="5%" stopColor={SAFFRON} stopOpacity={0.3} /><stop offset="95%" stopColor={SAFFRON} stopOpacity={0} /></linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" stroke="#E2E8F0" />
                <XAxis dataKey="date" tick={{ fontSize: 12, fill: SLATE }} tickLine={false} />
                <YAxis tick={{ fontSize: 12, fill: SLATE }} tickLine={false} axisLine={false} />
                <Tooltip contentStyle={{ backgroundColor: 'white', border: '1px solid #E2E8F0', borderRadius: '8px' }} />
                <Area type="monotone" dataKey="present" stroke={GREEN} fill="url(#colorPresent)" strokeWidth={2} name="Present" />
                <Area type="monotone" dataKey="late" stroke={SAFFRON} fill="url(#colorLate)" strokeWidth={2} name="Late" />
              </AreaChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>

        <Card className="border-0 shadow-sm">
          <CardHeader className="pb-0"><CardTitle className="text-lg text-navy-800">Hourly Check-ins</CardTitle></CardHeader>
          <CardContent className="p-6">
            <ResponsiveContainer width="100%" height={300}>
              <BarChart data={stats?.hourlyDistribution || []}>
                <CartesianGrid strokeDasharray="3 3" stroke="#E2E8F0" />
                <XAxis dataKey="hour" tick={{ fontSize: 11, fill: SLATE }} />
                <YAxis tick={{ fontSize: 11, fill: SLATE }} axisLine={false} />
                <Tooltip />
                <Bar dataKey="count" fill={NAVY} radius={[6, 6, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
