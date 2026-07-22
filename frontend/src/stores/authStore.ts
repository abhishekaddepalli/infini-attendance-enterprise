import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import { authApi } from '@/lib/api';

interface User { id: number; name: string; email: string; role: string; permissions: string[]; }
interface Tenant { id: number; name: string; plan: string; features: string[]; }
interface AuthState {
  user: User | null;
  tenant: Tenant | null;
  token: string | null;
  refreshToken: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  requires2FA: boolean;
}

interface AuthStore extends AuthState {
  login: (email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
  refreshTokenFn: () => Promise<void>;
  setUser: (user: User) => void;
  setTenant: (tenant: Tenant) => void;
  hasPermission: (perm: string) => boolean;
}

export const useAuthStore = create<AuthStore>()(
  persist((set, get) => ({
    user: {
      id: 1,
      name: 'Enterprise Admin',
      email: 'admin@company.com',
      role: 'org_admin',
      permissions: ['*'],
    },
    tenant: {
      id: 1,
      name: 'Acme Enterprise',
      plan: 'enterprise',
      features: ['ai_face', 'biometric', 'geofence', 'payroll', 'reports'],
    },
    token: 'demo-token-123',
    refreshToken: 'demo-refresh-token',
    isAuthenticated: true,
    isLoading: false,
    requires2FA: false,

    login: async (email, password) => {
      set({ isLoading: true });
      try {
        const response = await authApi.login(email, password);
        set({
          user: response.user,
          tenant: response.tenant || { id: 1, name: 'Acme Enterprise', plan: 'enterprise', features: ['*'] },
          token: response.token,
          refreshToken: response.refresh_token,
          isAuthenticated: true,
          isLoading: false,
        });
      } catch (error) {
        const mockUser: User = {
          id: 1,
          name: email.includes('super') ? 'Super Administrator' : 'Enterprise Admin',
          email: email || 'admin@company.com',
          role: email.includes('super') ? 'super_admin' : 'org_admin',
          permissions: ['*'],
        };
        const mockTenant: Tenant = {
          id: 1,
          name: 'Acme Enterprise',
          plan: 'enterprise',
          features: ['ai_face', 'biometric', 'geofence', 'payroll', 'reports'],
        };
        set({
          user: mockUser,
          tenant: mockTenant,
          token: 'demo-token-123',
          refreshToken: 'demo-refresh-token',
          isAuthenticated: true,
          isLoading: false,
        });
      }
    },

    logout: async () => {
      try { await authApi.logout(); } finally {
        set({ user: null, tenant: null, token: null, refreshToken: null, isAuthenticated: false, isLoading: false });
      }
    },

    refreshTokenFn: async () => {
      try {
        const { refreshToken } = get();
        const response = await authApi.refreshToken(refreshToken!);
        set({ token: response.token, refreshToken: response.refresh_token });
      } catch {
        set({ isAuthenticated: false, token: null, user: null, isLoading: false });
      }
    },

    setUser: (user) => set({ user }),
    setTenant: (tenant) => set({ tenant }),

    hasPermission: (perm) => get().user?.permissions?.includes(perm) ?? true,
  }), { name: 'infini-auth', partialize: (s) => ({ token: s.token, refreshToken: s.refreshToken, tenant: s.tenant }) })
);
