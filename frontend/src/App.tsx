import React, { Suspense, lazy } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { Toaster } from 'sonner';
import { useAuthStore } from '@/stores/authStore';
import { LoadingScreen } from '@/components/LoadingScreen';

const LandingPage = lazy(() => import('@/pages/home/LandingPage'));
const Login = lazy(() => import('@/pages/auth/Login'));
const TwoFactorAuth = lazy(() => import('@/pages/auth/TwoFactorAuth'));
const OrganizationDashboard = lazy(() => import('@/pages/dashboard/OrganizationDashboard'));
const OrganizationLayout = lazy(() => import('@/layouts/OrganizationLayout'));
const CheckoutPage = lazy(() => import('@/pages/payment/CheckoutPage'));
const FaceAttendance = lazy(() => import('@/pages/attendance/FaceAttendance'));
const EmployeeList = lazy(() => import('@/pages/employees/EmployeeList'));
const LeaveRequests = lazy(() => import('@/pages/leave/LeaveRequests'));
const ShiftSchedule = lazy(() => import('@/pages/shifts/ShiftSchedule'));
const PayrollProcessing = lazy(() => import('@/pages/payroll/PayrollProcessing'));
const DeviceDashboard = lazy(() => import('@/pages/devices/DeviceDashboard'));
const AIAssistant = lazy(() => import('@/pages/ai/AIAssistant'));
const Reports = lazy(() => import('@/pages/reports/Reports'));
const Settings = lazy(() => import('@/pages/settings/Settings'));
const SuperAdminDashboard = lazy(() => import('@/pages/super-admin/SuperAdminDashboard'));

const queryClient = new QueryClient({
  defaultOptions: {
    queries: { staleTime: 30000, retry: 2 },
    mutations: { retry: 1 },
  },
});

function ProtectedRoute({ children, roles }: { children: React.ReactNode; roles?: string[] }) {
  const { isAuthenticated, isLoading, user } = useAuthStore();
  if (isLoading) return <LoadingScreen />;
  if (!isAuthenticated) return <Navigate to="/login" replace />;
  if (roles && !roles.includes(user?.role || '')) return <Navigate to="/unauthorized" replace />;
  return <>{children}</>;
}

export default function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <BrowserRouter>
        <Suspense fallback={<LoadingScreen />}>
          <Routes>
            <Route path="/" element={<LandingPage />} />
            <Route path="/login" element={<Login />} />
            <Route path="/2fa" element={<TwoFactorAuth />} />
            <Route path="/checkout" element={<CheckoutPage />} />

            <Route path="/super-admin/*" element={
              <ProtectedRoute roles={['super_admin']}><SuperAdminDashboard /></ProtectedRoute>
            } />

            <Route path="/:tenant" element={
              <ProtectedRoute><OrganizationLayout /></ProtectedRoute>
            }>
              <Route index element={<Navigate to="dashboard" replace />} />
              <Route path="dashboard" element={<OrganizationDashboard />} />
              <Route path="attendance/face" element={<FaceAttendance />} />
              <Route path="employees" element={<EmployeeList />} />
              <Route path="leave" element={<LeaveRequests />} />
              <Route path="shifts" element={<ShiftSchedule />} />
              <Route path="payroll" element={<PayrollProcessing />} />
              <Route path="devices" element={<DeviceDashboard />} />
              <Route path="ai" element={<AIAssistant />} />
              <Route path="reports" element={<Reports />} />
              <Route path="settings" element={<Settings />} />
            </Route>

            <Route path="*" element={
              <div className="flex h-screen items-center justify-center">
                <div className="text-center">
                  <h1 className="text-6xl font-bold text-navy-800">404</h1>
                  <p className="mt-4 text-xl text-slate-500">Page not found</p>
                </div>
              </div>
            } />
          </Routes>
        </Suspense>
        <Toaster position="top-right" richColors closeButton duration={4000} />
      </BrowserRouter>
    </QueryClientProvider>
  );
}
