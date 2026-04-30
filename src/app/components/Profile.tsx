import { User, Mail, Shield, MapPin, Calendar, Briefcase } from 'lucide-react';

export function Profile() {
  const userInfo = {
    name: 'João Silva',
    email: 'joao.silva@ifpr.edu.br',
    role: 'Professor',
    campus: 'IFPR Campus Cascavel',
    department: 'Robótica e Automação',
    joinDate: '15/03/2024',
    profileImage: null,
  };

  return (
    <div className="p-4 md:p-8 bg-white min-h-full h-full flex items-center justify-center">
      <div className="max-w-2xl w-full">
        <div className="relative mb-8">
          <h1 className="border-4 border-foreground p-6 bg-[#0066FF] text-white inline-block text-3xl md:text-5xl tracking-tighter" style={{ fontWeight: 900 }}>
            PERFIL
          </h1>
          <div className="absolute -bottom-2 -right-2 w-8 h-8 bg-[#FF2D2D] border-4 border-black" />
        </div>

        <div className="border-4 border-black p-8 md:p-12 bg-[#f5f5f5] relative overflow-hidden">
          <div className="absolute top-0 left-0 w-full h-2 bg-[#FF2D2D]" />

          {/* Profile Image & Name */}
          <div className="flex flex-col items-center mb-8 mt-4">
            <div className="w-32 h-32 md:w-40 md:h-40 border-4 border-black bg-[#262626] flex items-center justify-center mb-6">
              {userInfo.profileImage ? (
                <img src={userInfo.profileImage} alt="Profile" className="w-full h-full object-cover" />
              ) : (
                <User size={80} strokeWidth={2} className="text-[#737373]" />
              )}
            </div>

            <h2 className="text-3xl md:text-4xl tracking-tighter mb-2 text-center" style={{ fontWeight: 900 }}>
              {userInfo.name}
            </h2>

            <div className="flex items-center gap-2 bg-black text-white px-4 py-2 border-4 border-black">
              <Shield size={20} strokeWidth={3} />
              <span className="tracking-tight" style={{ fontWeight: 900 }}>
                {userInfo.role.toUpperCase()}
              </span>
            </div>
          </div>

          {/* User Information Cards */}
          <div className="space-y-4">
            <InfoRow
              icon={<Mail size={24} strokeWidth={3} />}
              label="E-MAIL"
              value={userInfo.email}
              accent="border-[#0066FF]"
            />
            <InfoRow
              icon={<MapPin size={24} strokeWidth={3} />}
              label="CAMPUS"
              value={userInfo.campus}
              accent="border-[#FF2D2D]"
            />
            <InfoRow
              icon={<Briefcase size={24} strokeWidth={3} />}
              label="DEPARTAMENTO"
              value={userInfo.department}
              accent="border-[#0066FF]"
            />
            <InfoRow
              icon={<Calendar size={24} strokeWidth={3} />}
              label="MEMBRO DESDE"
              value={userInfo.joinDate}
              accent="border-[#525252]"
            />
          </div>

          {/* Stats */}
          <div className="mt-8 pt-8 border-t-4 border-[#d4d4d4]">
            <h3 className="text-xl tracking-tighter mb-4 border-l-4 border-[#FF2D2D] pl-4" style={{ fontWeight: 900 }}>
              ESTATÍSTICAS
            </h3>
            <div className="grid grid-cols-3 gap-4">
              <StatCard label="PROJETOS" value="12" color="bg-[#FF2D2D]" />
              <StatCard label="EQUIPES" value="3" color="bg-[#0066FF]" />
              <StatCard label="POSTS" value="28" color="bg-[#525252]" />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

interface InfoRowProps {
  icon: React.ReactNode;
  label: string;
  value: string;
  accent: string;
}

function InfoRow({ icon, label, value, accent }: InfoRowProps) {
  return (
    <div className={`border-4 ${accent} bg-white p-4 flex items-start gap-4`}>
      <div className="text-[#525252] mt-1">{icon}</div>
      <div className="flex-1">
        <label className="block text-xs text-[#737373] mb-1 tracking-tight" style={{ fontWeight: 700 }}>
          {label}
        </label>
        <p className="tracking-tight text-lg" style={{ fontWeight: 700 }}>{value}</p>
      </div>
    </div>
  );
}

interface StatCardProps {
  label: string;
  value: string;
  color: string;
}

function StatCard({ label, value, color }: StatCardProps) {
  return (
    <div className={`${color} text-white border-4 border-black p-4 text-center`}>
      <div className="text-3xl md:text-4xl tracking-tighter mb-1" style={{ fontWeight: 900 }}>
        {value}
      </div>
      <div className="text-xs tracking-tight" style={{ fontWeight: 700 }}>
        {label}
      </div>
    </div>
  );
}
