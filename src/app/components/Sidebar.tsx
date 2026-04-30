import { Home, FolderOpen, Users, MessageSquare, Settings, HelpCircle, LogIn, X } from 'lucide-react';

interface SidebarProps {
  currentView: string;
  setCurrentView: (view: 'home' | 'projects' | 'teams' | 'forum' | 'settings' | 'login' | 'profile') => void;
  mobileMenuOpen: boolean;
  setMobileMenuOpen: (open: boolean) => void;
}

export function Sidebar({ currentView, setCurrentView, mobileMenuOpen, setMobileMenuOpen }: SidebarProps) {
  const menuItems = [
    { id: 'home' as const, icon: Home, label: 'INÍCIO' },
    { id: 'projects' as const, icon: FolderOpen, label: 'PROJETOS' },
    { id: 'teams' as const, icon: Users, label: 'EQUIPES' },
    { id: 'forum' as const, icon: MessageSquare, label: 'FÓRUM' },
  ];

  const handleNavClick = (view: 'home' | 'projects' | 'teams' | 'forum' | 'settings' | 'login' | 'profile') => {
    setCurrentView(view);
    setMobileMenuOpen(false);
  };

  return (
    <>
      {/* Desktop Sidebar */}
      <aside className="hidden md:flex flex-col w-64 lg:w-80 bg-sidebar text-sidebar-foreground border-r-4 border-sidebar-border relative">
        <div className="absolute top-0 left-0 w-full h-2 bg-[#FF2D2D]" />

        <nav className="space-y-2 mt-6 px-4 flex-1">
          {menuItems.map((item, index) => {
            const Icon = item.icon;
            const isActive = currentView === item.id;

            return (
              <button
                key={item.id}
                onClick={() => handleNavClick(item.id)}
                className={`w-full flex items-center gap-3 p-4 border-4 transition-all tracking-tight relative ${
                  isActive
                    ? index % 2 === 0
                      ? 'bg-[#FF2D2D] text-white border-[#FF2D2D]'
                      : 'bg-[#0066FF] text-white border-[#0066FF]'
                    : 'border-sidebar-foreground hover:bg-[#262626] hover:text-white'
                }`}
                style={{ fontWeight: isActive ? 900 : 700 }}
              >
                <Icon size={24} strokeWidth={3} />
                <span>{item.label}</span>
                {isActive && (
                  <div className="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white" />
                )}
              </button>
            );
          })}
        </nav>

        <div className="mt-auto px-4 pb-4">
          <div className="mb-4 border-4 border-white p-6 bg-[#171717]">
            <h3 className="mb-4 tracking-tight border-b-2 border-[#404040] pb-2" style={{ fontWeight: 900 }}>
              ESTATÍSTICAS
            </h3>
            <div className="space-y-3">
              <div className="flex justify-between items-center">
                <span className="text-[#d4d4d4] text-sm">Projetos:</span>
                <span className="bg-[#FF2D2D] text-white px-3 py-1 border-2 border-white tracking-tight" style={{ fontWeight: 900 }}>
                  24
                </span>
              </div>
              <div className="flex justify-between items-center">
                <span className="text-[#d4d4d4] text-sm">Equipes:</span>
                <span className="bg-[#0066FF] text-white px-3 py-1 border-2 border-white tracking-tight" style={{ fontWeight: 900 }}>
                  8
                </span>
              </div>
              <div className="flex justify-between items-center">
                <span className="text-[#d4d4d4] text-sm">Componentes:</span>
                <span className="bg-[#525252] text-white px-3 py-1 border-2 border-white tracking-tight" style={{ fontWeight: 900 }}>
                  156
                </span>
              </div>
            </div>
          </div>

          <div className="space-y-2 bg-black border-4 border-white p-4">
            <button
              onClick={() => handleNavClick('login')}
              className={`w-full flex items-center gap-3 p-3 border-2 transition-all text-sm tracking-tight ${
                currentView === 'login'
                  ? 'bg-[#0066FF] text-white border-[#0066FF]'
                  : 'border-[#404040] hover:bg-[#262626] hover:border-white'
              }`}
              style={{ fontWeight: 700 }}
            >
              <LogIn size={18} strokeWidth={3} />
              <span>LOGIN</span>
            </button>
            <button
              onClick={() => handleNavClick('settings')}
              className={`w-full flex items-center gap-3 p-3 border-2 transition-all text-sm tracking-tight ${
                currentView === 'settings'
                  ? 'bg-[#FF2D2D] text-white border-[#FF2D2D]'
                  : 'border-[#404040] hover:bg-[#262626] hover:border-white'
              }`}
              style={{ fontWeight: 700 }}
            >
              <Settings size={18} strokeWidth={3} />
              <span>CONFIGURAÇÕES</span>
            </button>
            <button className="w-full flex items-center gap-3 p-3 border-2 border-[#404040] hover:bg-[#262626] hover:border-white transition-all text-sm tracking-tight" style={{ fontWeight: 700 }}>
              <HelpCircle size={18} strokeWidth={3} />
              <span>AJUDA</span>
            </button>
          </div>
        </div>
      </aside>

      {/* Mobile Sidebar */}
      {mobileMenuOpen && (
        <aside className="md:hidden fixed inset-0 z-50 bg-sidebar text-sidebar-foreground">
          <div className="absolute top-0 left-0 w-full h-2 bg-[#FF2D2D]" />

          <div className="flex justify-between items-center p-4 border-b-4 border-white mt-2">
            <h2 className="text-2xl tracking-tighter" style={{ fontWeight: 900 }}>MENU</h2>
            <button
              onClick={() => setMobileMenuOpen(false)}
              className="border-4 border-white p-2 hover:bg-[#FF2D2D] transition-colors"
            >
              <X size={24} strokeWidth={3} />
            </button>
          </div>

          <nav className="space-y-2 mt-4 px-4">
            {menuItems.map((item, index) => {
              const Icon = item.icon;
              const isActive = currentView === item.id;

              return (
                <button
                  key={item.id}
                  onClick={() => handleNavClick(item.id)}
                  className={`w-full flex items-center gap-3 p-4 border-4 transition-all tracking-tight ${
                    isActive
                      ? index % 2 === 0
                        ? 'bg-[#FF2D2D] text-white border-[#FF2D2D]'
                        : 'bg-[#0066FF] text-white border-[#0066FF]'
                      : 'border-sidebar-foreground hover:bg-[#262626] hover:text-white'
                  }`}
                  style={{ fontWeight: isActive ? 900 : 700 }}
                >
                  <Icon size={24} strokeWidth={3} />
                  <span>{item.label}</span>
                </button>
              );
            })}
          </nav>

          <div className="absolute bottom-0 left-0 right-0 p-4 space-y-2 bg-black border-t-4 border-white">
            <button
              onClick={() => handleNavClick('login')}
              className={`w-full flex items-center gap-3 p-3 border-2 transition-all text-sm tracking-tight ${
                currentView === 'login'
                  ? 'bg-[#0066FF] text-white border-[#0066FF]'
                  : 'border-[#404040] hover:bg-[#262626] hover:border-white'
              }`}
              style={{ fontWeight: 700 }}
            >
              <LogIn size={18} strokeWidth={3} />
              <span>LOGIN</span>
            </button>
            <button
              onClick={() => handleNavClick('settings')}
              className={`w-full flex items-center gap-3 p-3 border-2 transition-all text-sm tracking-tight ${
                currentView === 'settings'
                  ? 'bg-[#FF2D2D] text-white border-[#FF2D2D]'
                  : 'border-[#404040] hover:bg-[#262626] hover:border-white'
              }`}
              style={{ fontWeight: 700 }}
            >
              <Settings size={18} strokeWidth={3} />
              <span>CONFIGURAÇÕES</span>
            </button>
            <button className="w-full flex items-center gap-3 p-3 border-2 border-[#404040] hover:bg-[#262626] hover:border-white transition-all text-sm tracking-tight" style={{ fontWeight: 700 }}>
              <HelpCircle size={18} strokeWidth={3} />
              <span>AJUDA</span>
            </button>
          </div>
        </aside>
      )}
    </>
  );
}
