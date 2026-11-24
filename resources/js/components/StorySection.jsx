import React from 'react';
import { Sparkles, Compass, Leaf, Heart } from 'lucide-react';

export default function StorySection({ onBookNow }) {
  return (
    <div className="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        {/* Left: Imagery Grid */}
        <div className="grid grid-cols-2 gap-4 relative">
          <div className="space-y-4">
            <img
              src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=1938&auto=format&fit=crop"
              alt="Balinese Temple and Atmosphere"
              className="rounded-3xl shadow-lg w-full h-64 sm:h-80 object-cover"
            />
            <img
              src="https://images.unsplash.com/photo-1544644181-1484b3fdfc62?q=80&w=2070&auto=format&fit=crop"
              alt="Frangipani and Nature"
              className="rounded-3xl shadow-lg w-full h-44 sm:h-56 object-cover"
            />
          </div>
          <div className="space-y-4 pt-8">
            <img
              src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?q=80&w=2070&auto=format&fit=crop"
              alt="Tropical Balinese Architecture"
              className="rounded-3xl shadow-lg w-full h-48 sm:h-60 object-cover"
            />
            <img
              src="https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?q=80&w=1948&auto=format&fit=crop"
              alt="Sunset Ocean View"
              className="rounded-3xl shadow-lg w-full h-64 sm:h-72 object-cover"
            />
          </div>

          {/* Decorative Gold Crest Badge */}
          <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#122219] text-[#D8BD7D] p-5 rounded-full border-4 border-[#FAF8F5] shadow-2xl flex flex-col items-center justify-center text-center w-28 h-28">
            <span className="font-serif text-2xl font-bold">EST.</span>
            <span className="text-[10px] tracking-widest uppercase font-sans">BALI</span>
          </div>
        </div>

        {/* Right: Narrative Story */}
        <div className="lg:pl-6">
          <div className="inline-flex items-center gap-1.5 text-xs uppercase tracking-[0.28em] font-semibold text-[#A68742] mb-3">
            <Sparkles className="w-3.5 h-3.5" />
            <span>Filosofi & Warisan Luhur</span>
          </div>
          <h2 className="font-serif text-3xl sm:text-5xl font-normal text-[#122219] tracking-wide mb-6 leading-tight">
            Menemukan Ketenangan Sejati di Ujung Timur Pulau Dewata
          </h2>
          <div className="w-16 h-[2px] bg-[#C2A461] mb-6" />

          <p className="text-sm sm:text-base text-[#6A6054] font-light leading-relaxed mb-6">
            Tersembunyi di pesisir tenang Karangasem, **The East Hotel** dibangun
            berlandaskan filosofi sakral Bali **Tri Hita Karana** — keharmonisan abadi
            antara manusia, alam semesta, dan sang pencipta.
          </p>

          <p className="text-sm sm:text-base text-[#6A6054] font-light leading-relaxed mb-8">
            Di sini, setiap hembusan angin samudra membawa kedamaian, dan setiap
            sudut vila dihiasi pahatan batu lava hitam Gunung Agung, kayu jati daur
            ulang, dan paviliun bambu terbuka yang membiarkan alam menyatu tanpa sekat.
          </p>

          {/* 3 Pillars */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div className="p-4 rounded-2xl bg-white border border-[#E8DFC8]/60">
              <Compass className="w-5 h-5 text-[#C2A461] mb-2" />
              <h4 className="text-xs font-semibold text-[#122219] mb-1">
                Lokasi Pristin
              </h4>
              <p className="text-[11px] text-[#827566] font-light">
                Jauh dari hiruk pikuk kota, menghadap lautan lepas.
              </p>
            </div>
            <div className="p-4 rounded-2xl bg-white border border-[#E8DFC8]/60">
              <Leaf className="w-5 h-5 text-[#C2A461] mb-2" />
              <h4 className="text-xs font-semibold text-[#122219] mb-1">
                Arsitektur Alami
              </h4>
              <p className="text-[11px] text-[#827566] font-light">
                Material berkelanjutan dari perajin tradisional lokal.
              </p>
            </div>
            <div className="p-4 rounded-2xl bg-white border border-[#E8DFC8]/60">
              <Heart className="w-5 h-5 text-[#C2A461] mb-2" />
              <h4 className="text-xs font-semibold text-[#122219] mb-1">
                Layanan Tulus
              </h4>
              <p className="text-[11px] text-[#827566] font-light">
                Ketulusan senyum dan kehangatan khas masyarakat Bali.
              </p>
            </div>
          </div>

          <button
            onClick={onBookNow}
            className="px-8 py-3.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#122219] bg-[#C2A461] hover:bg-[#D8BD7D] rounded-full transition-all duration-300 shadow-md hover:shadow-lg"
          >
            Pesan Pengalaman Anda
          </button>
        </div>
      </div>
    </div>
  );
}
