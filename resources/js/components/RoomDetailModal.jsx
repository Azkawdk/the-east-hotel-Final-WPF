import React from 'react';
import { X, Users, Maximize, Bed, Check, Sparkles, Calendar, Coffee, Wifi, Shield } from 'lucide-react';

export default function RoomDetailModal({ roomType, isOpen, onClose, onBookThis }) {
  if (!isOpen || !roomType) return null;

  const formatIDR = (val) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(val || 0);
  };

  const defaultImg =
    roomType.image_url ||
    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop';

  const inclusions = [
    'Sarapan a la carte setiap pagi untuk seluruh tamu',
    'Minuman sambutan kelapa muda segar & handuk dingin aroma melati',
    'Akses tanpa batas ke Cliffside Infinity Pool & Fitness Pavillion',
    'Layanan teh & kopi Bali organik setiap sore',
    'Wi-Fi berkecepatan tinggi di seluruh area resor & vila',
    'Layanan kebersihan harian dengan perapian kamar malam (turn-down service)',
  ];

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 md:p-8 animate-fadeIn">
      {/* Backdrop */}
      <div
        className="fixed inset-0 bg-[#122219]/70 backdrop-blur-sm transition-opacity"
        onClick={onClose}
      />

      {/* Modal Dialog */}
      <div className="relative bg-[#FAF8F5] rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-[#E8DFC8] z-10 flex flex-col">
        {/* Close Button */}
        <button
          onClick={onClose}
          className="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-[#122219] flex items-center justify-center shadow-md transition-all hover:scale-105"
        >
          <X className="w-5 h-5" />
        </button>

        {/* Hero Photo */}
        <div className="relative h-72 sm:h-96 w-full overflow-hidden bg-[#122219]">
          <img
            src={defaultImg}
            alt={roomType.name}
            className="w-full h-full object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />
          <div className="absolute bottom-6 left-6 right-6 text-white">
            <span className="inline-block px-3 py-1 rounded-full bg-[#C2A461]/90 text-[10px] uppercase font-bold tracking-[0.2em] mb-2 text-[#122219]">
              {roomType.bed_type || 'Luxury Suite'}
            </span>
            <h2 className="font-serif text-3xl sm:text-4xl font-normal">
              {roomType.name}
            </h2>
          </div>
        </div>

        {/* Modal Body */}
        <div className="p-6 sm:p-8 space-y-8">
          {/* Top Info Bar */}
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-white border border-[#E8DFC8]/70 text-center">
            <div>
              <span className="block text-[10px] uppercase tracking-wider text-[#827566] mb-1">
                Kapasitas
              </span>
              <span className="text-sm font-semibold text-[#122219] flex items-center justify-center gap-1">
                <Users className="w-3.5 h-3.5 text-[#C2A461]" />
                Maks. {roomType.capacity} Tamu
              </span>
            </div>
            <div>
              <span className="block text-[10px] uppercase tracking-wider text-[#827566] mb-1">
                Ukuran
              </span>
              <span className="text-sm font-semibold text-[#122219] flex items-center justify-center gap-1">
                <Maximize className="w-3.5 h-3.5 text-[#C2A461]" />
                {roomType.size_m2 || 45} m²
              </span>
            </div>
            <div>
              <span className="block text-[10px] uppercase tracking-wider text-[#827566] mb-1">
                Tempat Tidur
              </span>
              <span className="text-sm font-semibold text-[#122219] flex items-center justify-center gap-1">
                <Bed className="w-3.5 h-3.5 text-[#C2A461]" />
                {roomType.bed_type || 'King'}
              </span>
            </div>
            <div>
              <span className="block text-[10px] uppercase tracking-wider text-[#827566] mb-1">
                Harga Sewa
              </span>
              <span className="text-sm font-bold text-[#A68742]">
                {formatIDR(roomType.base_price)}{' '}
                <span className="text-[10px] font-normal text-[#827566]">/malam</span>
              </span>
            </div>
          </div>

          {/* Description */}
          <div>
            <h3 className="text-xs uppercase font-bold tracking-[0.2em] text-[#827566] mb-2">
              Tentang Ruangan
            </h3>
            <p className="text-sm text-[#4A4036] font-light leading-relaxed whitespace-pre-line">
              {roomType.description}
            </p>
          </div>

          {/* Amenities & Facilities */}
          {roomType.amenities && roomType.amenities.length > 0 && (
            <div>
              <h3 className="text-xs uppercase font-bold tracking-[0.2em] text-[#827566] mb-3">
                Fasilitas Kamar Spesifik
              </h3>
              <div className="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                {roomType.amenities.map((am) => (
                  <div
                    key={am.id}
                    className="flex items-center gap-2 text-xs text-[#26221F] bg-white p-2.5 rounded-xl border border-[#E8DFC8]/60"
                  >
                    <Check className="w-3.5 h-3.5 text-[#C2A461] shrink-0" />
                    <span>{am.name}</span>
                  </div>
                ))}
              </div>
            </div>
          )}

          {/* Complimentary Inclusions */}
          <div className="bg-[#122219]/5 rounded-2xl p-5 border border-[#C2A461]/30">
            <h3 className="text-xs uppercase font-bold tracking-[0.2em] text-[#122219] mb-3 flex items-center gap-1.5">
              <Sparkles className="w-4 h-4 text-[#C2A461]" />
              Fasilitas Istimewa Termasuk Dalam Reservasi
            </h3>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-[#4A4036]">
              {inclusions.map((item, idx) => (
                <div key={idx} className="flex items-start gap-2">
                  <Check className="w-3.5 h-3.5 text-[#C2A461] shrink-0 mt-0.5" />
                  <span>{item}</span>
                </div>
              ))}
            </div>
          </div>

          {/* Bottom Action Footer */}
          <div className="pt-4 border-t border-[#E8DFC8] flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
              <span className="text-xs text-[#827566]">Tarif Reservasi Resmi</span>
              <div className="font-serif text-2xl font-bold text-[#122219]">
                {formatIDR(roomType.base_price)}
                <span className="text-xs font-sans font-light text-[#827566]">
                  {' '}
                  / malam (sebelum pajak & service)
                </span>
              </div>
            </div>

            <button
              onClick={() => onBookThis(roomType)}
              className="w-full sm:w-auto px-8 py-3.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#122219] bg-[#C2A461] hover:bg-[#D8BD7D] rounded-full transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2"
            >
              <Calendar className="w-4 h-4" />
              Lanjutkan Pemesanan
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
