import { User, Mail, Shield, Calendar, Camera } from 'lucide-react';

export function Settings() {
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
    <div className="p-4 md:p-8 bg-white min-h-full h-full">
      <div className="relative mb-8">
        <h1 className="border-4 border-foreground p-6 bg-black text-white inline-block text-3xl md:text-5xl tracking-tighter" style={{ fontWeight: 900 }}>
          CONFIGURAÇÕES
        </h1>
        <div className="absolute -bottom-2 -right-2 w-8 h-8 bg-[#FF2D2D] border-4 border-black" />
      </div>

      <div className="max-w-4xl">
        {/* Profile Section */}
        <div className="border-4 border-black p-8 bg-[#f5f5f5] mb-6 relative overflow-hidden">
          <div className="absolute top-0 left-0 w-full h-2 bg-[#0066FF]" />

          <h2 className="text-2xl md:text-3xl tracking-tighter mb-6 mt-2 border-l-4 border-[#FF2D2D] pl-4" style={{ fontWeight: 900 }}>
            PERFIL DO USUÁRIO
          </h2>

          {/* Profile Image */}
          <div className="flex flex-col md:flex-row gap-8 mb-8">
            <div className="relative">
              <div className="w-32 h-32 md:w-40 md:h-40 border-4 border-black bg-[#262626] flex items-center justify-center">
                {userInfo.profileImage ? (
                  <img src={userInfo.profileImage} alt="Profile" className="w-full h-full object-cover" />
                ) : (
                  <User size={64} strokeWidth={2} className="text-[#737373]" />
                )}
              </div>
              <button className="absolute -bottom-2 -right-2 bg-[#0066FF] text-white border-4 border-black p-2 hover:bg-[#FF2D2D] transition-colors">
                <Camera size={20} strokeWidth={3} />
              </button>
            </div>

            <div className="flex-1 space-y-4">
              <div>
                <label className="block text-sm text-[#525252] mb-2 tracking-tight" style={{ fontWeight: 700 }}>
                  NOME COMPLETO
                </label>
                <input
                  type="text"
                  value={userInfo.name}
                  className="w-full border-4 border-black px-4 py-3 bg-white tracking-tight"
                  style={{ fontWeight: 700 }}
                  readOnly
                />
              </div>

              <div>
                <label className="block text-sm text-[#525252] mb-2 tracking-tight" style={{ fontWeight: 700 }}>
                  E-MAIL INSTITUCIONAL
                </label>
                <div className="flex items-center gap-3 w-full border-4 border-black px-4 py-3 bg-white">
                  <Mail size={20} strokeWidth={3} className="text-[#525252]" />
                  <span className="tracking-tight" style={{ fontWeight: 700 }}>{userInfo.email}</span>
                </div>
              </div>
            </div>
          </div>

          {/* User Info Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <InfoCard
              icon={<Shield size={24} strokeWidth={3} />}
              label="FUNÇÃO"
              value={userInfo.role}
              accent="bg-[#FF2D2D]"
            />
            <InfoCard
              icon={<User size={24} strokeWidth={3} />}
              label="CAMPUS"
              value={userInfo.campus}
              accent="bg-[#0066FF]"
            />
            <InfoCard
              icon={<User size={24} strokeWidth={3} />}
              label="DEPARTAMENTO"
              value={userInfo.department}
              accent="bg-[#525252]"
            />
            <InfoCard
              icon={<Calendar size={24} strokeWidth={3} />}
              label="DATA DE CADASTRO"
              value={userInfo.joinDate}
              accent="bg-[#404040]"
            />
          </div>
        </div>

        {/* Account Settings */}
        <div className="border-4 border-black p-8 bg-[#fafafa] mb-6 relative overflow-hidden">
          <div className="absolute top-0 left-0 w-full h-2 bg-[#FF2D2D]" />

          <h2 className="text-2xl md:text-3xl tracking-tighter mb-6 mt-2 border-l-4 border-[#0066FF] pl-4" style={{ fontWeight: 900 }}>
            CONFIGURAÇÕES DA CONTA
          </h2>

          <div className="space-y-4">
            <button className="w-full flex items-center justify-between p-4 border-4 border-black bg-white hover:bg-[#f5f5f5] transition-colors">
              <span className="tracking-tight" style={{ fontWeight: 700 }}>Alterar Senha</span>
              <div className="w-2 h-2 bg-[#FF2D2D]" />
            </button>
            <button className="w-full flex items-center justify-between p-4 border-4 border-black bg-white hover:bg-[#f5f5f5] transition-colors">
              <span className="tracking-tight" style={{ fontWeight: 700 }}>Notificações</span>
              <div className="w-2 h-2 bg-[#0066FF]" />
            </button>
            <button className="w-full flex items-center justify-between p-4 border-4 border-black bg-white hover:bg-[#f5f5f5] transition-colors">
              <span className="tracking-tight" style={{ fontWeight: 700 }}>Privacidade</span>
              <div className="w-2 h-2 bg-[#FF2D2D]" />
            </button>
            <button className="w-full flex items-center justify-between p-4 border-4 border-black bg-white hover:bg-[#f5f5f5] transition-colors">
              <span className="tracking-tight" style={{ fontWeight: 700 }}>Preferências de Exibição</span>
              <div className="w-2 h-2 bg-[#0066FF]" />
            </button>
          </div>
        </div>

        {/* Permissions */}
        <div className="border-4 border-black p-8 bg-[#f5f5f5] relative overflow-hidden">
          <div className="absolute top-0 left-0 w-full h-2 bg-[#525252]" />

          <h2 className="text-2xl md:text-3xl tracking-tighter mb-6 mt-2 border-l-4 border-[#FF2D2D] pl-4" style={{ fontWeight: 900 }}>
            PERMISSÕES
          </h2>

          <div className="space-y-3">
            <PermissionItem label="Criar Projetos" enabled={true} />
            <PermissionItem label="Gerenciar Equipes" enabled={true} />
            <PermissionItem label="Moderar Fórum" enabled={true} />
            <PermissionItem label="Aprovar Publicações" enabled={true} />
            <PermissionItem label="Exportar Dados" enabled={false} />
          </div>
        </div>

        {/* Danger Zone */}
        <div className="border-4 border-[#FF2D2D] p-8 bg-black text-white mt-6 relative overflow-hidden">
          <div className="absolute top-0 left-0 w-full h-2 bg-[#FF2D2D]" />

          <h2 className="text-2xl md:text-3xl tracking-tighter mb-4 mt-2" style={{ fontWeight: 900 }}>
            ZONA DE PERIGO
          </h2>
          <p className="text-[#d4d4d4] mb-6">Ações irreversíveis que afetam sua conta permanentemente.</p>

          <button className="bg-[#FF2D2D] text-white border-4 border-white px-6 py-3 hover:bg-white hover:text-[#FF2D2D] transition-colors tracking-tight" style={{ fontWeight: 900 }}>
            DESATIVAR CONTA
          </button>
        </div>
      </div>
    </div>
  );
}

interface InfoCardProps {
  icon: React.ReactNode;
  label: string;
  value: string;
  accent: string;
}

function InfoCard({ icon, label, value, accent }: InfoCardProps) {
  return (
    <div className="border-4 border-black bg-white p-4 relative overflow-hidden">
      <div className={`absolute top-0 right-0 w-1 h-full ${accent}`} />
      <div className="flex items-start gap-3">
        <div className="text-[#525252] mt-1">{icon}</div>
        <div className="flex-1">
          <label className="block text-xs text-[#737373] mb-1 tracking-tight" style={{ fontWeight: 700 }}>
            {label}
          </label>
          <p className="tracking-tight" style={{ fontWeight: 700 }}>{value}</p>
        </div>
      </div>
    </div>
  );
}

interface PermissionItemProps {
  label: string;
  enabled: boolean;
}

function PermissionItem({ label, enabled }: PermissionItemProps) {
  return (
    <div className="flex items-center justify-between p-4 border-2 border-[#d4d4d4] bg-white">
      <span className="tracking-tight" style={{ fontWeight: 700 }}>{label}</span>
      <div className={`w-12 h-6 border-4 border-black ${enabled ? 'bg-[#0066FF]' : 'bg-[#525252]'} flex items-center ${enabled ? 'justify-end' : 'justify-start'} px-0.5`}>
        <div className="w-4 h-4 bg-white border-2 border-black" />
      </div>
    </div>
  );
}
