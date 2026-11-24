import React from 'react';
import { Users, Maximize, Bed, Check, Sparkles, ArrowRight, Eye } from 'lucide-react';

// Fallback high-res luxury Bali imagery if room_type image_url is empty
const defaultImages = [
  'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop', // Deluxe Garden
  'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop', // Ocean Suite
  'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop', // Pool Villa
  'https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=2074&auto=format&fit=crop', // Family Villa
  'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop',
];

export default function RoomTypesSection({ roomTypes, loading, onSelectRoom, onViewDetail }) {
  // Format IDR currency
  const formatIDR = (val) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(val || 0);
  };

  return (
    <div className="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
      {/* Section Header */}
      <div className="text-center max-w-3xl mx-auto mb-16">
        <div className="inline-flex items-center gap-1.5 text-xs uppercase tracking-[0.28em] font-semibold text-[#A68742] mb-3">
          <Sparkles className="w-3.5 h-3.5" />
          <span>Akomodasi & Vila Eksklusif</span>
        </div>
        <h2 className="font-serif text-3xl sm:text-5xl font-normal text-[#122219] tracking-wide mb-5">
          Suites & Private Pool Villas
        </h2>
        <div className="w-16 h-[2px] bg-[#C2A461] mx-auto mb-5" />
        <p className="text-sm sm:text-base text-[#6A6054] font-light leading-relaxed">
          Setiap kamar dirancang sebagai tempat peristirahatan privat dengan
          arsitektur terbuka Bali, batu alam, kayu jati pilihan, dan keasrian taman tropis.
        </p>
      </div>

      {/* Loading Skeleton */}
      {loading && (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {[1, 2, 3].map((i) => (
            <div
              key={i}
              className="bg-white rounded-2xl overflow-hidden border border-[#E8DFC8]/60 animate-pulse shadow-sm"
            >
              <div className="h-64 bg-[#EFE8DC]" />
              <div className="p-6 space-y-4">
                <div className="h-5 bg-[#EFE8DC] rounded w-3/4" />
                <div className="h-3 bg-[#EFE8DC] rounded w-full" />
                <div className="h-3 bg-[#EFE8DC] rounded w-1/2" />
                <div className="h-10 bg-[#EFE8DC] rounded" />
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Room Grid */}
      {!loading && roomTypes.length > 0 && (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10">
          {roomTypes.map((room, idx) => {
            const imgSrc =
              room.image_url || defaultImages[idx % defaultImages.length];

            return (
              <div
                key={room.id}
                className="group bg-white rounded-2xl overflow-hidden border border-[#E8DFC8]/80 shadow-[0_10px_30px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_45px_rgba(37,70,53,0.12)] transition-all duration-500 flex flex-col hover:-translate-y-1.5"
              >
                {/* Room Image Container */}
                <div className="relative h-64 sm:h-72 overflow-hidden bg-[#122219]">
                  <img
                    src={imgSrc}
                    alt={room.name}
                    className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-108"
                    loading="lazy"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent" />

                  {/* Bed Type Badge */}
                  {room.bed_type && (
                    <div className="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider text-[#122219] shadow-sm">
                      {room.bed_type}
                    </div>
                  )}

                  {/* Price Tag Overlay */}
                  <div className="absolute bottom-4 left-4 right-4 flex items-end justify-between text-white">
                    <div>
                      <div className="text-[10px] uppercase tracking-widest text-[#E8DFC8] font-light">
                        Mulai dari
                      </div>
                      <div className="font-serif text-2xl font-semibold text-white">
                        {formatIDR(room.base_price)}
                        <span className="text-xs font-sans font-light text-white/80">
                          {' '}
                          / malam
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Content */}
                <div className="p-6 flex-1 flex flex-col justify-between">
                  <div>
                    <h3 className="font-serif text-xl sm:text-2xl font-medium text-[#122219] mb-2 group-hover:text-[#A68742] transition-colors">
                      {room.name}
                    </h3>

                    <p className="text-xs sm:text-sm text-[#6A6054] font-light leading-relaxed mb-5 line-clamp-2">
                      {room.description}
                    </p>

                    {/* Specifications */}
                    <div className="grid grid-cols-3 gap-2 py-3 border-y border-[#E8DFC8]/60 text-center mb-5">
                      <div className="flex flex-col items-center">
                        <Users className="w-4 h-4 text-[#C2A461] mb-1" />
                        <span className="text-[11px] font-semibold text-[#122219]">
                          {room.capacity} Tamu
                        </span>
                        <span className="text-[9px] text-[#827566]">Kapasitas</span>
                      </div>
                      <div className="flex flex-col items-center border-x border-[#E8DFC8]/60">
                        <Maximize className="w-4 h-4 text-[#C2A461] mb-1" />
                        <span className="text-[11px] font-semibold text-[#122219]">
                          {room.size_m2 || 45} m²
                        </span>
                        <span className="text-[9px] text-[#827566]">Luas Kamar</span>
                      </div>
                      <div className="flex flex-col items-center">
                        <Bed className="w-4 h-4 text-[#C2A461] mb-1" />
                        <span className="text-[11px] font-semibold text-[#122219] truncate max-w-full px-1">
                          {room.bed_type || 'King'}
                        </span>
                        <span className="text-[9px] text-[#827566]">Ranjang</span>
                      </div>
                    </div>

                    {/* Amenities tags */}
                    {room.amenities && room.amenities.length > 0 && (
                      <div className="flex flex-wrap gap-1.5 mb-6">
                        {room.amenities.slice(0, 4).map((am) => (
                          <span
                            key={am.id}
                            className="inline-flex items-center text-[10px] bg-[#FAF8F5] text-[#54483C] px-2.5 py-1 rounded-md border border-[#E8DFC8]/50"
                          >
                            <Check className="w-2.5 h-2.5 text-[#C2A461] mr-1" />
                            {am.name}
                          </span>
                        ))}
                        {room.amenities.length > 4 && (
                          <span className="text-[10px] text-[#827566] px-1.5 py-1">
                            +{room.amenities.length - 4} lainnya
                          </span>
                        )}
                      </div>
                    )}
                  </div>

                  {/* Actions */}
                  <div className="grid grid-cols-2 gap-2 pt-2">
                    <button
                      onClick={() => onViewDetail(room)}
                      className="px-3 py-2.5 text-xs font-medium tracking-wider text-[#54483C] bg-[#FAF8F5] hover:bg-[#EFE8DC] rounded-xl border border-[#E8DFC8] flex items-center justify-center gap-1.5 transition-colors"
                    >
                      <Eye className="w-3.5 h-3.5" />
                      Detail
                    </button>
                    <button
                      onClick={() => onSelectRoom(room)}
                      className="px-3 py-2.5 text-xs font-semibold uppercase tracking-wider text-[#122219] bg-[#C2A461] hover:bg-[#D8BD7D] rounded-xl flex items-center justify-center gap-1.5 transition-all shadow-sm group-hover:shadow"
                    >
                      <span>Booking</span>
                      <ArrowRight className="w-3.5 h-3.5" />
                    </button>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}

      {/* Empty State */}
      {!loading && roomTypes.length === 0 && (
        <div className="text-center py-16 bg-white rounded-3xl border border-[#E8DFC8] p-8 max-w-xl mx-auto shadow-sm">
          <Sparkles className="w-12 h-12 text-[#C2A461] mx-auto mb-4" />
          <h3 className="font-serif text-2xl font-normal text-[#122219] mb-2">
            Belum Ada Tipe Kamar Aktif
          </h3>
          <p className="text-sm text-[#6A6054] mb-6">
            Kamar dapat dikonfigurasi melalui panel admin hotel. Anda juga dapat langsung menghubungi concierge kami untuk reservasi khusus.
          </p>
          <a
            href="/admin/login"
            className="inline-flex items-center px-6 py-2.5 rounded-full text-xs font-semibold uppercase tracking-wider text-white bg-[#122219] hover:bg-[#1A3326]"
          >
            Kelola di Admin Panel
          </a>
        </div>
      )}
    </div>
  );
}
