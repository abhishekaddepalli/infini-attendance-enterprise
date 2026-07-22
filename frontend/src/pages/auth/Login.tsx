import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { useNavigate } from 'react-router-dom';
import { Eye, EyeOff, Mail, Lock, Chrome, Building2, Fingerprint, Shield } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { toast } from 'sonner';
import { useAuthStore } from '@/stores/authStore';

export default function Login() {
  const navigate = useNavigate();
  const { login } = useAuthStore();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [loginMethod, setLoginMethod] = useState<'password' | 'face' | 'biometric'>('password');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    try {
      await login(email, password);
      toast.success('Welcome back!');
      navigate('/dashboard', { replace: true });
    } catch (err: any) {
      toast.error(err?.response?.data?.message || 'Login failed');
    } finally { setIsLoading(false); }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-navy-50 via-white to-saffron-50 flex">
      {/* Brand Panel */}
      <div className="hidden lg:flex lg:w-1/2 bg-navy-800 relative overflow-hidden items-center justify-center">
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,153,51,0.1),transparent_70%)]" />
        <motion.div initial={{ opacity: 0, y: 30 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.6 }} className="relative z-10 text-white px-16">
          <div className="flex items-center gap-3 mb-8">
            <div className="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center">
              <span className="text-3xl font-bold text-saffron-400">I</span>
            </div>
            <div>
              <h2 className="text-3xl font-bold">Infini<span className="text-saffron-400">.</span></h2>
              <p className="text-sm text-white/60">Attendance</p>
            </div>
          </div>
          <h1 className="text-5xl font-bold leading-tight mb-6">Smart Attendance.<br /><span className="text-saffron-400">Smarter Workforce.</span></h1>
          <p className="text-lg text-white/70 leading-relaxed">Enterprise-grade workforce management platform trusted by organizations worldwide.</p>
        </motion.div>
      </div>

      {/* Login Form */}
      <div className="flex-1 flex items-center justify-center px-6">
        <motion.div initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }} transition={{ duration: 0.5 }} className="w-full max-w-md">
          <div className="mb-8">
            <h2 className="text-3xl font-bold text-navy-800">Welcome back</h2>
            <p className="mt-2 text-slate-500">Sign in to your organization account</p>
          </div>

          <div className="flex gap-2 mb-6 bg-slate-100 rounded-xl p-1">
            {[
              { id: 'password' as const, icon: Mail, label: 'Password' },
              { id: 'face' as const, icon: Shield, label: 'Face ID' },
              { id: 'biometric' as const, icon: Fingerprint, label: 'Biometric' },
            ].map(({ id, icon: Icon, label }) => (
              <button key={id} onClick={() => setLoginMethod(id)}
                className={`flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-medium transition-all ${
                  loginMethod === id ? 'bg-white text-navy-800 shadow-sm' : 'text-slate-500'}`}>
                <Icon className="h-4 w-4" />{label}
              </button>
            ))}
          </div>

          {loginMethod === 'password' && (
            <form onSubmit={handleSubmit} className="space-y-5">
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                <div className="relative">
                  <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" />
                  <Input type="email" value={email} onChange={e => setEmail(e.target.value)} placeholder="admin@company.com" className="pl-10" required />
                </div>
              </div>
              <div>
                <div className="flex items-center justify-between mb-1.5">
                  <label className="block text-sm font-medium text-slate-700">Password</label>
                  <a href="/forgot-password" className="text-sm text-navy-600">Forgot?</a>
                </div>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" />
                  <Input type={showPassword ? 'text' : 'password'} value={password} onChange={e => setPassword(e.target.value)} className="pl-10 pr-10" required />
                  <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                    {showPassword ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
                  </button>
                </div>
              </div>
              <Button type="submit" variant="premium" size="xl" className="w-full" loading={isLoading}>Sign In</Button>
            </form>
          )}

          {loginMethod !== 'password' && (
            <div className="text-center py-8">
              <div className="w-20 h-20 rounded-2xl bg-navy-50 flex items-center justify-center mx-auto mb-4">
                {loginMethod === 'face' ? <Shield className="h-10 w-10 text-navy-800" /> : <Fingerprint className="h-10 w-10 text-navy-800" />}
              </div>
              <h3 className="text-lg font-medium text-navy-800 mb-2">{loginMethod === 'face' ? 'Face Recognition' : 'Biometric Auth'}</h3>
              <p className="text-slate-500 mb-6">Use your device {loginMethod === 'face' ? 'camera' : 'fingerprint'} for secure login</p>
              <Button variant="premium" size="lg" onClick={() => toast.info('Biometric login in development')}>
                Authenticate
              </Button>
            </div>
          )}
        </motion.div>
      </div>
    </div>
  );
}
