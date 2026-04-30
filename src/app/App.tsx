import { useState } from 'react';
import { Header } from './components/Header';
import { Hero } from './components/Hero';
import { ProjectGrid } from './components/ProjectGrid';
import { Sidebar } from './components/Sidebar';
import { Settings } from './components/Settings';
import { Profile } from './components/Profile';

export default function App() {
  const [currentView, setCurrentView] = useState<'home' | 'projects' | 'teams' | 'forum' | 'settings' | 'login' | 'profile'>('home');
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <div className="size-full bg-background text-foreground flex flex-col">
      <Header
        currentView={currentView}
        setCurrentView={setCurrentView}
        mobileMenuOpen={mobileMenuOpen}
        setMobileMenuOpen={setMobileMenuOpen}
      />

      <div className="flex flex-1 overflow-hidden">
        <Sidebar
          currentView={currentView}
          setCurrentView={setCurrentView}
          mobileMenuOpen={mobileMenuOpen}
          setMobileMenuOpen={setMobileMenuOpen}
        />

        <main className="flex-1 overflow-y-auto h-full">
          {currentView === 'home' && <Hero />}
          {currentView === 'projects' && <ProjectGrid />}
          {currentView === 'settings' && <Settings />}
          {currentView === 'profile' && <Profile />}
          {currentView === 'login' && (
            <div className="p-8 bg-white min-h-screen flex items-center justify-center">
              <div className="border-4 border-black p-8 md:p-12 bg-[#f5f5f5] max-w-md w-full">
                <h1 className="text-4xl md:text-5xl tracking-tighter mb-8 border-l-8 border-[#FF2D2D] pl-4" style={{ fontWeight: 900 }}>
                  LOGIN
                </h1>
                <div className="space-y-4">
                  <input
                    type="email"
                    placeholder="E-mail"
                    className="w-full border-4 border-black px-4 py-3 bg-white"
                  />
                  <input
                    type="password"
                    placeholder="Senha"
                    className="w-full border-4 border-black px-4 py-3 bg-white"
                  />
                  <button className="w-full bg-[#0066FF] text-white border-4 border-black px-6 py-4 hover:bg-[#FF2D2D] transition-colors tracking-tight" style={{ fontWeight: 900 }}>
                    ENTRAR
                  </button>
                </div>
              </div>
            </div>
          )}
          {currentView === 'teams' && (
            <div className="p-8 bg-white min-h-screen">
              <div className="relative mb-8">
                <h1 className="border-4 border-foreground p-6 bg-[#0066FF] text-white inline-block text-3xl md:text-5xl tracking-tighter" style={{ fontWeight: 900 }}>
                  EQUIPES
                </h1>
                <div className="absolute -bottom-2 -right-2 w-8 h-8 bg-[#FF2D2D] border-4 border-black" />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {['Equipe Alpha', 'Equipe Beta', 'Equipe Gamma', 'Equipe Delta', 'Equipe Epsilon'].map((team, index) => (
                  <div key={team} className="border-4 border-black p-6 bg-[#f5f5f5] hover:bg-white transition-colors relative">
                    <div className={`absolute top-0 left-0 w-full h-2 ${index % 2 === 0 ? 'bg-[#FF2D2D]' : 'bg-[#0066FF]'}`} />
                    <h3 className="text-xl tracking-tight mb-4 mt-2" style={{ fontWeight: 900 }}>{team}</h3>
                    <div className="space-y-2 text-sm text-[#525252]">
                      <p>Campus: IFPR Cascavel</p>
                      <p>Membros: {4 + index}</p>
                      <p>Projetos: {2 + index}</p>
                    </div>
                    <button className="mt-4 w-full bg-black text-white border-4 border-black px-4 py-2 hover:bg-[#FF2D2D] transition-colors tracking-tight" style={{ fontWeight: 700 }}>
                      VER EQUIPE
                    </button>
                  </div>
                ))}
              </div>
            </div>
          )}
          {currentView === 'forum' && (
            <div className="p-8 bg-white min-h-screen">
              <div className="relative mb-8">
                <h1 className="border-4 border-foreground p-6 bg-[#FF2D2D] text-white inline-block text-3xl md:text-5xl tracking-tighter" style={{ fontWeight: 900 }}>
                  FÓRUM
                </h1>
                <div className="absolute -bottom-2 -right-2 w-8 h-8 bg-[#0066FF] border-4 border-black" />
              </div>

              <div className="space-y-4">
                {[
                  { title: 'Como otimizar código para Arduino', author: 'João Silva', replies: 12, category: 'Programação' },
                  { title: 'Melhores motores para robô seguidor', author: 'Maria Santos', replies: 8, category: 'Hardware' },
                  { title: 'Problemas com sensor ultrassônico', author: 'Pedro Oliveira', replies: 5, category: 'Suporte' },
                  { title: 'Estratégias para competição de sumô', author: 'Ana Costa', replies: 15, category: 'Competição' },
                ].map((topic, index) => (
                  <div key={index} className="border-4 border-black p-6 bg-[#fafafa] hover:bg-white transition-colors relative">
                    <div className={`absolute left-0 top-0 w-2 h-full ${index % 2 === 0 ? 'bg-[#FF2D2D]' : 'bg-[#0066FF]'}`} />
                    <div className="pl-4">
                      <div className="flex items-start justify-between mb-2">
                        <h3 className="text-xl tracking-tight flex-1" style={{ fontWeight: 900 }}>{topic.title}</h3>
                        <span className={`${index % 2 === 0 ? 'bg-[#FF2D2D]' : 'bg-[#0066FF]'} text-white px-3 py-1 text-sm border-2 border-black tracking-tight ml-4`} style={{ fontWeight: 700 }}>
                          {topic.category}
                        </span>
                      </div>
                      <div className="flex items-center gap-4 text-sm text-[#525252]">
                        <span>Por: {topic.author}</span>
                        <span className="border-2 border-[#d4d4d4] px-2 py-1">{topic.replies} respostas</span>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </main>
      </div>
    </div>
  );
}
