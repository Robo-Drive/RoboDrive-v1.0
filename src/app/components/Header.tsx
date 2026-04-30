import { Menu, X, User } from 'lucide-react';

interface HeaderProps {
  currentView: string;
  setCurrentView: (view: 'home' | 'projects' | 'teams' | 'forum' | 'settings' | 'login' | 'profile') => void;
  mobileMenuOpen: boolean;
  setMobileMenuOpen: (open: boolean) => void;
}

export function Header({ currentView, setCurrentView, mobileMenuOpen, setMobileMenuOpen }: HeaderProps) {
  return (
    <header className="bg-primary text-primary-foreground border-b-4 border-foreground p-4 md:p-6 relative overflow-hidden">
      <div className="absolute inset-0 opacity-5 pointer-events-none">
        <div className="absolute top-0 left-0 w-full h-full" style={{
          backgroundImage: `repeating-linear-gradient(0deg, transparent, transparent 2px, currentColor 2px, currentColor 4px)`
        }} />
      </div>

      <div className="flex items-center justify-between relative z-10 max-w-screen-2xl mx-auto">
        {/* Logo IFPR - Esquerda */}
        <div className="flex items-center gap-2 border-4 border-primary-foreground p-2 md:p-3 hover:bg-[#262626] transition-colors cursor-pointer">
          <div className="flex items-center">
            <span className="text-3xl md:text-4xl tracking-tighter" style={{ fontWeight: 900 }}>
              I
            </span>
            <span className="text-3xl md:text-4xl tracking-tighter ml-1" style={{ fontWeight: 900 }}>
              F
            </span>
          </div>
          <span className="hidden md:block text-sm tracking-tight ml-2" style={{ fontWeight: 700 }}>
            IFPR
          </span>
        </div>

        {/* Logo ROBODRIVE - Centro */}
        <button
          onClick={() => {
            setCurrentView('home');
            setMobileMenuOpen(false);
          }}
          className="absolute left-1/2 -translate-x-1/2 hover:bg-[#FF2D2D] hover:text-white transition-colors border-4 border-primary-foreground p-2 md:p-3"
        >
          <h1 className="text-xl md:text-3xl lg:text-4xl tracking-tighter" style={{ fontWeight: 900 }}>
            ROBODRIVE
          </h1>
        </button>

        {/* User Avatar e Menu - Direita */}
        <div className="flex items-center gap-3">
          {/* Desktop: Avatar */}
          <button
            onClick={() => setCurrentView('profile')}
            className="hidden md:flex items-center justify-center w-12 h-12 lg:w-14 lg:h-14 border-4 border-primary-foreground bg-[#0066FF] hover:bg-[#FF2D2D] transition-colors"
          >
            <User size={24} strokeWidth={3} />
          </button>

          {/* Mobile: Menu Hamburger + Avatar */}
          <button
            onClick={() => setCurrentView('profile')}
            className="md:hidden flex items-center justify-center w-10 h-10 border-4 border-primary-foreground bg-[#0066FF] hover:bg-[#FF2D2D] transition-colors"
          >
            <User size={20} strokeWidth={3} />
          </button>

          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="md:hidden border-4 border-primary-foreground p-2 hover:bg-[#FF2D2D] transition-colors"
          >
            {mobileMenuOpen ? (
              <X size={24} strokeWidth={3} />
            ) : (
              <Menu size={24} strokeWidth={3} />
            )}
          </button>
        </div>
      </div>
    </header>
  );
}
