import { Code, Users, Database, MessageSquare } from 'lucide-react';

export function Hero() {
  return (
    <div className="p-4 md:p-8 bg-white">
      <div className="relative border-4 border-foreground bg-black text-white p-8 md:p-16 mb-8 overflow-hidden">
        <div className="absolute top-4 right-4 text-[#FF2D2D] text-6xl md:text-9xl opacity-20 tracking-tighter" style={{ fontWeight: 900 }}>
          X X X
        </div>

        <div className="relative z-10">
          <div className="inline-block bg-[#FF2D2D] px-4 py-2 mb-6">
            <span className="text-xs md:text-sm tracking-wider" style={{ fontWeight: 700 }}>
              IFPR · SISTEMA DE GESTÃO
            </span>
          </div>

          <h1 className="text-5xl md:text-8xl mb-6 leading-none tracking-tighter" style={{ fontWeight: 900 }}>
            REPOSITÓRIO
            <br />
            <span className="text-[#0066FF]">DE PROJETOS</span>
            <br />
            DE ROBÓTICA
          </h1>

          <p className="text-lg md:text-2xl max-w-3xl mt-8 text-[#e5e5e5]" style={{ fontWeight: 400 }}>
            Centralize, organize e compartilhe projetos de robótica educacional
          </p>

          <button className="mt-8 bg-[#FF2D2D] text-white border-4 border-white px-8 py-4 hover:bg-[#0066FF] transition-colors text-xl tracking-tight" style={{ fontWeight: 900 }}>
            COMEÇAR AGORA →
          </button>
        </div>

        <div className="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-black to-transparent opacity-50" />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-0">
        <FeatureCard
          icon={<Code size={48} strokeWidth={3} />}
          title="CÓDIGO"
          description="Armazene e versione códigos dos seus robôs"
          bgColor="bg-[#171717]"
          borderColor="border-[#FF2D2D]"
          accentColor="text-[#FF2D2D]"
        />
        <FeatureCard
          icon={<Database size={48} strokeWidth={3} />}
          title="COMPONENTES"
          description="Catalogue componentes reutilizáveis"
          bgColor="bg-[#262626]"
          borderColor="border-[#0066FF]"
          accentColor="text-[#0066FF]"
        />
        <FeatureCard
          icon={<Users size={48} strokeWidth={3} />}
          title="EQUIPES"
          description="Colabore com sua equipe e campus"
          bgColor="bg-[#404040]"
          borderColor="border-[#FF2D2D]"
          accentColor="text-[#FF2D2D]"
        />
        <FeatureCard
          icon={<MessageSquare size={48} strokeWidth={3} />}
          title="FÓRUM"
          description="Discuta e compartilhe conhecimento"
          bgColor="bg-[#525252]"
          borderColor="border-[#0066FF]"
          accentColor="text-[#0066FF]"
        />
      </div>

      <div className="mt-8 border-4 border-foreground p-8 md:p-12 bg-[#f5f5f5] relative overflow-hidden">
        <div className="absolute top-0 right-0 w-64 h-64 border-4 border-[#e5e5e5] rotate-45 -mr-32 -mt-32" />

        <div className="relative z-10">
          <h2 className="text-4xl md:text-6xl mb-6 tracking-tighter inline-block border-l-8 border-[#FF2D2D] pl-4" style={{ fontWeight: 900 }}>
            SOBRE O
            <br />
            PROJETO
          </h2>

          <div className="space-y-4 text-base md:text-lg max-w-4xl">
            <p className="text-[#404040]">
              O RoboDrive é uma plataforma desenvolvida para resolver o problema da dispersão
              de conhecimento em projetos de robótica educacional.
            </p>
            <p className="text-[#404040]">
              Quando códigos, documentações e decisões técnicas ficam espalhados em dispositivos
              pessoais e mensagens, perde-se a capacidade de reaproveitar esse conhecimento
              em turmas futuras.
            </p>

            <div className="flex flex-wrap gap-4 pt-4">
              <span className="bg-black text-white px-6 py-3 border-4 border-black tracking-tight" style={{ fontWeight: 700 }}>
                CENTRALIZE
              </span>
              <span className="bg-[#0066FF] text-white px-6 py-3 border-4 border-black tracking-tight" style={{ fontWeight: 700 }}>
                ORGANIZE
              </span>
              <span className="bg-[#FF2D2D] text-white px-6 py-3 border-4 border-black tracking-tight" style={{ fontWeight: 700 }}>
                REUTILIZE
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

interface FeatureCardProps {
  icon: React.ReactNode;
  title: string;
  description: string;
  bgColor: string;
  borderColor: string;
  accentColor: string;
}

function FeatureCard({ icon, title, description, bgColor, borderColor, accentColor }: FeatureCardProps) {
  return (
    <div className={`${bgColor} text-white border-4 ${borderColor} p-6 md:p-8 hover:scale-105 transition-transform relative overflow-hidden group`}>
      <div className={`absolute top-2 right-2 ${accentColor} opacity-20 group-hover:opacity-40 transition-opacity`}>
        <div className="text-4xl" style={{ fontWeight: 900 }}>*</div>
      </div>

      <div className={`mb-4 ${accentColor}`}>{icon}</div>
      <h3 className="text-xl md:text-2xl mb-2 tracking-tight" style={{ fontWeight: 900 }}>{title}</h3>
      <p className="text-sm md:text-base text-[#d4d4d4]">{description}</p>
    </div>
  );
}
