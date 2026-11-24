import React from 'react';
import { Sparkles, Waves, Utensils, Flower2, Car, HeartHandshake } from 'lucide-react';

const signatureExperiences = [
  {
    title: 'Cliffside Infinity Pool',
    category: 'Relaksasi Tirta',
    image:
      'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop',
    description:
      'Berenang di tepi tebing yang menghadap langsung ke perairan biru Selat Lombok, berpadu dengan lanskap Gunung Rinjani di kejauhan.',
  },
  {
    title: 'The Sacred Frangipani Spa',
    category: 'Kebugaran Tradisional',
    image:
      'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=2070&auto=format&fit=crop',
    description:
      'Ritual pemulihan holistik dengan teknik pijat Bali kuno, lulur rempah boreh alami, dan mandi bunga di bawah naungan bambu.',
  },
  {
    title: 'Samudra Oceanfront Dining',
    category: 'Kuliner Nusantara & Global',
    image:
      'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop',
    description:
      'Kelezatan hasil tangkapan laut segar dari nelayan lokal Kusamba dan bahan organik pulau Bali, dinikmati tepat di tepi deburan ombak.',
  },
  {
    title: 'Private Chauffeur & Airport Service',
    category: 'Kenyamanan Eksklusif',
    image:
      'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=2070&auto=format&fit=crop',
    description:
      'Perjalanan mulus dari Bandara Internasional Ngurah Rai langsung ke peristirahatan Anda dengan armada mewah dan pramutamu pribadi.',
  },
];

export default function ExperiencesSection({ amenities }) {
  return (
    <div className="bg-[#FAF8F5] py-24 px-4 sm:px-6 lg:px-8 border-t border-[#E8DFC8]/60">
      <div className="max-w-7xl mx-auto">
        {/* Header */}
        <div className="text-center max-w-3xl mx-auto mb-16">
          <div className="inline-flex items-center gap-1.5 text-xs uppercase tracking-[0.28em] font-semibold text-[#A68742] mb-3">
            <Sparkles className="w-3.5 h-3.5" />
            <span>Harmoni Jiwa & Raga</span>
          </div>
          <h2 className="font-serif text-3xl sm:text-5xl font-normal text-[#122219] tracking-wide mb-5">
            Pengalaman & Fasilitas Resor
          </h2>
          <div className="w-16 h-[2px] bg-[#C2A461] mx-auto mb-5" />
          <p className="text-sm sm:text-base text-[#6A6054] font-light leading-relaxed">
            Menghidupkan kembali tradisi luhur keramahan Bali melalui relaksasi,
            kuliner autentik, dan ketenangan alam tropis yang autentik.
          </p>
        </div>

        {/* 4 Large Highlight Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
          {signatureExperiences.map((exp, idx) => (
            <div
              key={idx}
              className="group relative rounded-3xl overflow-hidden shadow-[0_15px_35px_rgba(0,0,0,0.06)] border border-[#E8DFC8] bg-white flex flex-col sm:flex-row transition-all duration-500 hover:-translate-y-1"
            >
              <div className="sm:w-1/2 h-64 sm:h-auto overflow-hidden relative">
                <img
                  src={exp.image}
                  alt={exp.title}
                  className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-108"
                  loading="lazy"
                />
                <div className="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors" />
              </div>
              <div className="sm:w-1/2 p-6 sm:p-8 flex flex-col justify-center">
                <span className="text-[10px] uppercase font-bold tracking-[0.2em] text-[#A68742] mb-2">
                  {exp.category}
                </span>
                <h3 className="font-serif text-2xl font-normal text-[#122219] mb-3 group-hover:text-[#A68742] transition-colors">
                  {exp.title}
                </h3>
                <p className="text-xs sm:text-sm text-[#6A6054] font-light leading-relaxed">
                  {exp.description}
                </p>
              </div>
            </div>
          ))}
        </div>

        {/* Live Amenities from Backend API */}
        {amenities && amenities.length > 0 && (
          <div className="bg-white rounded-3xl p-8 sm:p-10 border border-[#E8DFC8] shadow-sm">
            <h3 className="font-serif text-2xl text-center font-normal text-[#122219] mb-8">
              Koleksi Lengkap Fasilitas Hotel & Kamar
            </h3>
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
              {amenities.map((item) => (
                <div
                  key={item.id}
                  className="p-4 rounded-2xl bg-[#FAF8F5] border border-[#E8DFC8]/60 flex items-start gap-3 hover:border-[#C2A461] transition-colors"
                >
                  <div className="w-8 h-8 rounded-full bg-[#C2A461]/15 text-[#A68742] flex items-center justify-center shrink-0 mt-0.5">
                    <Sparkles className="w-4 h-4" />
                  </div>
                  <div>
                    <h4 className="text-xs font-semibold text-[#122219]">
                      {item.name}
                    </h4>
                    {item.description && (
                      <p className="text-[11px] text-[#827566] font-light mt-0.5">
                        {item.description}
                      </p>
                    )}
                    <span className="inline-block mt-1 text-[9px] uppercase tracking-wider text-[#A68742] font-medium">
                      {item.scope === 'hotel' ? 'Fasilitas Resor' : 'Fasilitas Kamar'}
                    </span>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
