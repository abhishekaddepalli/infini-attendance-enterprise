import React, { useState } from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import {
  LayoutDashboard, UserCheck, Users, CalendarCheck, Clock,
  Fingerprint, MapPin, DollarSign, FileText, Settings,
  Bot, ChevronDown, Menu, X,
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Badge } from '@/components/ui/badge';

const navItems = [
  { title: 'Dashboard', icon: LayoutDashboard, path: '/dashboard' },
  { title: 'Attendance', icon: UserCheck, path: '/attendance', children: [
    { title: 'Face Recognition', path: '/attendance/face' },
    { title: 'Live View', path: '/attendance/live' },
  ]},
  { title: 'Employees', icon: Users, path: '/employees' },
  { title: 'Leave', icon: CalendarCheck, path: '/leave' },
  { title: 'Shifts', icon: Clock, path: '/shifts' },
  { title: 'Devices', icon: Fingerprint, path: '/devices' },
  { title: 'GPS Tracking', icon: MapPin, path: '/geo' },
  { title: 'Payroll', icon: DollarSign, path: '/payroll' },
  { title: 'Reports', icon: FileText, path: '/reports' },
  { title: 'AI Assistant', icon: Bot, path: '/ai' },
  { title: 'Settings', icon: Settings, path: '/settings' },
];

export default function OrganizationLayout() {
  const location = useLocation();
  const [openMenus, setOpenMenus] = useState<string[]>(['Attendance']);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const toggleMenu = (title: string) => {
    setOpenMenus(prev => prev.includes(title) ? prev.filter(t => t !== title) : [...prev, title]);
  };

  const isActive = (path: string) => location.pathname.includes(path);

  const SidebarContent = () => (
    <div className="flex flex-col h-full">
      <div className="flex items-center gap-2 px-4 py-4 border-b">
        <div className="w-8 h-8 bg-navy-800 rounded-lg flex items-center justify-center">
          <span className="text-sm font-bold text-white">I</span>
        </div>
        <span className="font-bold text-navy-800">Infini<span className="text-saffron-500">.</span></span>
      </div>
      <ScrollArea className="flex-1 px-2 py-3">
        {navItems.map((item) => (
          <div key={item.title}>
            {item.children ? (
              <>
                <button onClick={() => toggleMenu(item.title)}
                  className={cn(
                    'flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                    item.children.some(c => isActive(c.path)) ? 'bg-navy-50 text-navy-800' : 'text-slate-600 hover:bg-slate-50'
                  )}>
                  <div className="flex items-center gap-3"><item.icon className="h-5 w-5" /><span>{item.title}</span></div>
                  <ChevronDown className={cn('h-4 w-4 transition-transform', openMenus.includes(item.title) ? 'rotate-180' : '')} />
                </button>
                {openMenus.includes(item.title) && (
                  <div className="ml-9 mt-1 space-y-1 border-l-2 border-slate-100 pl-4">
                    {item.children.map(child => (
                      <a key={child.path} href={child.path}
                        className={cn('block rounded-lg px-3 py-2 text-sm transition-colors',
                          isActive(child.path) ? 'bg-saffron-50 text-saffron-700 font-medium' : 'text-slate-500 hover:text-navy-800')}>
                        {child.title}
                      </a>
                    ))}
                  </div>
                )}
              </>
            ) : (
              <a href={item.path!}
                className={cn('flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                  isActive(item.path!) ? 'bg-navy-50 text-navy-800' : 'text-slate-600 hover:bg-slate-50')}>
                <item.icon className="h-5 w-5" /><span>{item.title}</span>
              </a>
            )}
          </div>
        ))}
      </ScrollArea>
    </div>
  );

  return (
    <div className="min-h-screen bg-slate-50 flex">
      {/* Desktop Sidebar */}
      <aside className="hidden lg:flex lg:w-64 lg:flex-col fixed left-0 top-0 h-screen bg-white border-r shadow-sm z-30">
        <SidebarContent />
      </aside>

      {/* Mobile Sidebar */}
      {mobileMenuOpen && (
        <div className="fixed inset-0 z-50 lg:hidden">
          <div className="fixed inset-0 bg-black/50" onClick={() => setMobileMenuOpen(false)} />
          <aside className="fixed left-0 top-0 h-full w-72 bg-white shadow-lg">
            <div className="flex justify-end p-2"><button onClick={() => setMobileMenuOpen(false)}><X className="h-6 w-6" /></button></div>
            <SidebarContent />
          </aside>
        </div>
      )}

      {/* Main Content */}
      <div className="flex-1 lg:ml-64">
        <header className="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b px-6 py-3 flex items-center justify-between lg:hidden">
          <button onClick={() => setMobileMenuOpen(true)}><Menu className="h-6 w-6" /></button>
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 bg-navy-800 rounded-lg flex items-center justify-center">
              <span className="text-sm font-bold text-white">I</span>
            </div>
            <span className="font-bold text-navy-800">Infini</span>
          </div>
        </header>
        <main><Outlet /></main>
      </div>
    </div>
  );
}
