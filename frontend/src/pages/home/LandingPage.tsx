import React from 'react';
import { Link } from 'react-router-dom';
import { Shield, Clock, Users, Cpu, Award, ArrowRight, CheckCircle2, Building2, Sparkles, Lock } from 'lucide-react';
import { Button } from '@/components/ui/button';

export default function LandingPage() {
  return (
    <div className="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white">
      {/* Header Navigation */}
      <header className="sticky top-0 z-50 backdrop-blur-md bg-slate-950/80 border-b border-slate-800/80">
        <div className="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center font-bold text-white text-xl shadow-lg shadow-indigo-500/20">
              I
            </div>
            <div>
              <span className="text-xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                Infini.
              </span>
              <span className="text-xs uppercase tracking-widest block text-indigo-400 font-semibold -mt-1">
                Attendance Enterprise
              </span>
            </div>
          </div>

          <div className="hidden md:flex items-center gap-8 text-sm font-medium text-slate-400">
            <a href="#features" className="hover:text-indigo-400 transition-colors">Features</a>
            <a href="#solutions" className="hover:text-indigo-400 transition-colors">Solutions</a>
            <a href="#architecture" className="hover:text-indigo-400 transition-colors">Security</a>
            <a href="#installer" className="hover:text-indigo-400 transition-colors">Deployment</a>
          </div>

          <div className="flex items-center gap-4">
            <Link to="/login">
              <Button variant="ghost" className="text-slate-300 hover:text-white hover:bg-slate-800">
                Sign In
              </Button>
            </Link>
            <Link to="/login">
              <Button variant="premium" className="shadow-lg shadow-indigo-500/25">
                Launch Portal <ArrowRight className="w-4 h-4 ml-1.5" />
              </Button>
            </Link>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="relative pt-24 pb-32 overflow-hidden">
        <div className="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(99,102,241,0.25),rgba(255,255,255,0))]"></div>
        <div className="max-w-7xl mx-auto px-6 relative z-10 text-center">
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold uppercase tracking-wider mb-8">
            <Sparkles className="w-3.5 h-3.5 text-indigo-400" /> AI-Powered Workforce & Attendance Suite v2.0
          </div>
          
          <h1 className="text-5xl md:text-7xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight">
            Smart Attendance. <br />
            <span className="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
              Smarter Workforce.
            </span>
          </h1>

          <p className="mt-6 text-lg md:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed">
            Next-generation enterprise attendance management featuring AI face recognition, IoT biometric hardware gateway, geofenced tracking, and automated multi-tenant payroll engine.
          </p>

          <div className="mt-10 flex flex-wrap items-center justify-center gap-4">
            <Link to="/login">
              <Button variant="premium" className="h-12 px-8 text-base shadow-xl shadow-indigo-600/30">
                Get Started <ArrowRight className="w-5 h-5 ml-2" />
              </Button>
            </Link>
            <a href="/installer/index.php" target="_blank" rel="noreferrer">
              <Button variant="outline" className="h-12 px-8 text-base border-slate-700 bg-slate-900/60 hover:bg-slate-800 text-slate-200">
                Setup Wizard
              </Button>
            </a>
          </div>

          {/* Feature Highlights Grid */}
          <div className="mt-20 grid grid-cols-1 md:grid-cols-4 gap-6 text-left">
            <div className="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-indigo-500/40 transition-all group">
              <div className="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-4 group-hover:scale-110 transition-transform">
                <Cpu className="w-6 h-6" />
              </div>
              <h3 className="font-bold text-lg text-slate-100">AI Face Recognition</h3>
              <p className="text-sm text-slate-400 mt-2">Liveness detection with sub-second facial feature matching for spoof-proof attendance.</p>
            </div>

            <div className="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-indigo-500/40 transition-all group">
              <div className="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 transition-transform">
                <Shield className="w-6 h-6" />
              </div>
              <h3 className="font-bold text-lg text-slate-100">Biometric Devices</h3>
              <p className="text-sm text-slate-400 mt-2">Universal hardware sync supporting ZKTEco, Hikvision, and custom IoT fingerprint terminals.</p>
            </div>

            <div className="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-indigo-500/40 transition-all group">
              <div className="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4 group-hover:scale-110 transition-transform">
                <Clock className="w-6 h-6" />
              </div>
              <h3 className="font-bold text-lg text-slate-100">Geofence & GPS</h3>
              <p className="text-sm text-slate-400 mt-2">Field staff location verification with dynamic radius bounds and mobile PWA check-in.</p>
            </div>

            <div className="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-indigo-500/40 transition-all group">
              <div className="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center text-pink-400 mb-4 group-hover:scale-110 transition-transform">
                <Building2 className="w-6 h-6" />
              </div>
              <h3 className="font-bold text-lg text-slate-100">Multi-Tenant SaaS</h3>
              <p className="text-sm text-slate-400 mt-2">Complete organization isolation with white-label branding, custom domains, and subscription management.</p>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="border-t border-slate-800/80 bg-slate-950 py-12 text-slate-500 text-sm">
        <div className="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
          <p>© {new Date().getFullYear()} Infini Attendance Enterprise. All rights reserved.</p>
          <div className="flex gap-6">
            <Link to="/login" className="hover:text-slate-300">Organization Login</Link>
            <Link to="/super-admin" className="hover:text-slate-300">Super Admin Portal</Link>
          </div>
        </div>
      </footer>
    </div>
  );
}
