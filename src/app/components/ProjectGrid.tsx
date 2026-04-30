import { Plus, Eye, Lock, Globe, ArrowRight } from 'lucide-react';

const mockProjects = [
  {
    id: 1,
    name: 'ROBÔ SEGUIDOR DE LINHA',
    team: 'Equipe Alpha',
    campus: 'IFPR Campus Cascavel',
    visibility: 'team' as const,
    components: 8,
    lastUpdate: '2026-04-20',
  },
  {
    id: 2,
    name: 'BRAÇO ROBÓTICO 3DOF',
    team: 'Equipe Beta',
    campus: 'IFPR Campus Curitiba',
    visibility: 'campus' as const,
    components: 12,
    lastUpdate: '2026-04-18',
  },
  {
    id: 3,
    name: 'SUMÔ AUTÔNOMO',
    team: 'Equipe Gamma',
    campus: 'IFPR Campus Londrina',
    visibility: 'public' as const,
    components: 15,
    lastUpdate: '2026-04-15',
  },
  {
    id: 4,
    name: 'DRONE FPV RACING',
    team: 'Equipe Delta',
    campus: 'IFPR Campus Cascavel',
    visibility: 'public' as const,
    components: 20,
    lastUpdate: '2026-04-10',
  },
];

export function ProjectGrid() {
  return (
    <div className="p-4 md:p-8 bg-white min-h-screen">
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div className="relative">
          <h1 className="border-4 border-foreground p-6 bg-black text-white inline-block text-3xl md:text-5xl tracking-tighter" style={{ fontWeight: 900 }}>
            PROJETOS
          </h1>
          <div className="absolute -top-2 -right-2 w-8 h-8 bg-[#FF2D2D] border-4 border-black" />
        </div>

        <button className="bg-[#0066FF] text-white border-4 border-foreground px-6 py-4 hover:bg-[#FF2D2D] transition-colors flex items-center gap-3 tracking-tight group" style={{ fontWeight: 900 }}>
          <Plus size={24} strokeWidth={3} />
          NOVO PROJETO
          <ArrowRight size={20} strokeWidth={3} className="group-hover:translate-x-1 transition-transform" />
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {mockProjects.map((project, index) => (
          <ProjectCard key={project.id} project={project} index={index} />
        ))}
      </div>
    </div>
  );
}

interface Project {
  id: number;
  name: string;
  team: string;
  campus: string;
  visibility: 'team' | 'campus' | 'public';
  components: number;
  lastUpdate: string;
}

function ProjectCard({ project, index }: { project: Project; index: number }) {
  const visibilityConfig = {
    team: { icon: Lock, label: 'EQUIPE', color: 'bg-[#525252]', textColor: 'text-white' },
    campus: { icon: Eye, label: 'CAMPUS', color: 'bg-[#0066FF]', textColor: 'text-white' },
    public: { icon: Globe, label: 'PÚBLICO', color: 'bg-[#e5e5e5]', textColor: 'text-black' },
  };

  const config = visibilityConfig[project.visibility];
  const Icon = config.icon;

  const bgColors = ['bg-white', 'bg-[#fafafa]', 'bg-[#f5f5f5]', 'bg-white'];
  const accentColors = ['border-[#FF2D2D]', 'border-[#0066FF]', 'border-[#FF2D2D]', 'border-[#0066FF]'];

  return (
    <div className={`border-4 border-foreground p-6 md:p-8 ${bgColors[index % 4]} hover:bg-[#f5f5f5] transition-colors cursor-pointer group relative overflow-hidden`}>
      <div className={`absolute top-0 left-0 w-2 h-full ${index % 2 === 0 ? 'bg-[#FF2D2D]' : 'bg-[#0066FF]'}`} />

      <div className="flex items-start justify-between mb-4 pl-4">
        <h3 className="text-2xl md:text-3xl flex-1 tracking-tighter leading-tight" style={{ fontWeight: 900 }}>
          {project.name}
        </h3>
        <div className={`${config.color} ${config.textColor} border-4 border-foreground p-2 flex items-center gap-2 ml-4`}>
          <Icon size={20} strokeWidth={3} />
          <span className="text-xs hidden md:inline tracking-tight" style={{ fontWeight: 700 }}>
            {config.label}
          </span>
        </div>
      </div>

      <div className="space-y-3 text-sm md:text-base pl-4">
        <div className="flex gap-2">
          <span className="bg-black text-white px-4 py-2 border-2 border-black tracking-tight" style={{ fontWeight: 700 }}>
            {project.team}
          </span>
        </div>

        <p className="text-[#525252]">{project.campus}</p>

        <div className="flex flex-wrap gap-3 pt-2">
          <span className="border-2 border-[#d4d4d4] px-4 py-2 text-[#525252] tracking-tight">
            {project.components} COMPONENTES
          </span>
          <span className="border-2 border-[#d4d4d4] px-4 py-2 text-[#525252] tracking-tight">
            {new Date(project.lastUpdate).toLocaleDateString('pt-BR')}
          </span>
        </div>
      </div>

      <button className={`mt-6 w-full bg-black text-white border-4 ${accentColors[index % 4]} px-4 py-3 group-hover:bg-[${index % 2 === 0 ? '#FF2D2D' : '#0066FF'}] transition-colors flex items-center justify-between tracking-tight`} style={{ fontWeight: 900 }}>
        <span>ABRIR PROJETO</span>
        <ArrowRight size={20} strokeWidth={3} className="group-hover:translate-x-1 transition-transform" />
      </button>
    </div>
  );
}
