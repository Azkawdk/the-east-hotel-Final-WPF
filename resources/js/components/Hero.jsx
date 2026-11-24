import React from 'react';
import { Calendar, Users, ArrowRight, Sparkles } from 'lucide-react';

export default function Hero({ searchParams, setSearchParams, onSearch, onBookNow }) {
  // Default dates
  const today = new Date().toISOString().split('T')[0];
  const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];

  return (
    <div className="relative min-h-[92vh] flex items-center justify-center overflow-hidden">
      {/* Background Hero Image with Luxury Overlay */}
      <div className="absolute inset-0 z-0">
        <img
          src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080&auto=format&fit=crop"
          alt="The East Hotel Bali Luxury Pool and Ocean"
          className="w-full h-full object-cover scale-105 animate-pulse duration-[10000ms]"
        />
        {/* Soft luxury film overlays */}
        <div className="absolute inset-0 bg-gradient-to-t from-[#122219]/90 via-[#122219]/45 to-black/35" />
        <div className="absolute inset-0 bg-radial from-transparent via-transparent to-[#122219]/60" />
      </div>

      {/* Hero Content */}
      <div className="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white pt-24 pb-16">
        {/* Luxury Pill Tag */}
        <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-6 text-[#D8BD7D] text-xs uppercase tracking-[0.25em] font-medium animate-fadeIn">
          <Sparkles className="w-3.5 h-3.5 text-[#C2A461]" />
          <span>Sanctuarium Tepi Tebing &bull; Bali Timur</span>
        </div>

        {/* Main Title */}
        <h1 className="font-serif text-4xl sm:text-6xl md:text-7xl font-normal tracking-wide text-white leading-[1.1] mb-6 drop-shadow-md">
          Tempat Jiwa Beristirahat <br />
          <span className="italic font-light text-[#E8DFC8]">
            di Pelukan Samudra Hindia
          </span>
        </h1>

        {/* Subtitle */}
        <p className="max-w-2xl mx-auto text-sm sm:text-base md:text-lg text-[#F2ECE1]/90 font-light leading-relaxed tracking-wider mb-12">
          Rasakan keheningan arsitektur tropis Bali, vila kolam renang pribadi,
          dan hembusan angin samudra yang murni di tebing eksklusif Karangasem.
        </p>

        {/* Floating Luxury Quick Availability Bar */}
        <div className="max-w-4xl mx-auto glass-card rounded-2xl p-4 sm:p-5 shadow-[0_20px_50px_rgba(0,0,0,0.35)] border border-white/25 text-[#1C1917]">
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 items-center">
            {/* Check-In */}
            <div className="bg-white/80 rounded-xl p-3 text-left border border-[#E8DFC8]/60 focus-within:border-[#C2A461] transition-all">
              <label className="block text-[10px] uppercase font-bold tracking-[0.18em] text-[#827566] mb-1">
                Check-in
              </label>
              <div className="flex items-center gap-2">
                <Calendar className="w-4 h-4 text-[#C2A461] shrink-0" />
                <input
                  type="date"
                  min={today}
                  value={searchParams.check_in || today}
                  onChange={(e) =>
                    setSearchParams({ ...searchParams, check_in: e.target.value })
                  }
                  className="w-full bg-transparent text-xs font-semibold text-[#1C1917] outline-none cursor-pointer"
                />
              </div>
            </div>

            {/* Check-Out */}
            <div className="bg-white/80 rounded-xl p-3 text-left border border-[#E8DFC8]/60 focus-within:border-[#C2A461] transition-all">
              <label className="block text-[10px] uppercase font-bold tracking-[0.18em] text-[#827566] mb-1">
                Check-out
              </label>
              <div className="flex items-center gap-2">
                <Calendar className="w-4 h-4 text-[#C2A461] shrink-0" />
                <input
                  type="date"
                  min={searchParams.check_in || today}
                  value={searchParams.check_out || tomorrow}
                  onChange={(e) =>
                    setSearchParams({ ...searchParams, check_out: e.target.value })
                  }
                  className="w-full bg-transparent text-xs font-semibold text-[#1C1917] outline-none cursor-pointer"
                />
              </div>
            </div>

            {/* Guests */}
            <div className="bg-white/80 rounded-xl p-3 text-left border border-[#E8DFC8]/60 focus-within:border-[#C2A461] transition-all">
              <label className="block text-[10px] uppercase font-bold tracking-[0.18em] text-[#827566] mb-1">
                Jumlah Tamu
              </label>
              <div className="flex items-center gap-2">
                <Users className="w-4 h-4 text-[#C2A461] shrink-0" />
                <select
                  value={searchParams.guests || 2}
                  onChange={(e) =>
                    setSearchParams({ ...searchParams, guests: Number(e.target.value) })
                  }
                  className="w-full bg-transparent text-xs font-semibold text-[#1C1917] outline-none cursor-pointer"
                >
                  <option value={1}>1 Tamu (Solo)</option>
                  <option value={2}>2 Tamu (Pasangan)</option>
                  <option value={3}>3 Tamu</option>
                  <option value={4}>4 Tamu (Keluarga)</option>
                  <option value={6}>6+ Tamu (Rombongan)</option>
                </select>
              </div>
            </div>

            {/* CTA Button */}
            <button
              onClick={onBookNow}
              className="w-full h-full min-h-[52px] bg-[#122219] hover:bg-[#1A3326] text-[#D8BD7D] hover:text-white font-semibold text-xs uppercase tracking-[0.2em] rounded-xl flex items-center justify-center gap-2 transition-all duration-300 shadow-lg hover:shadow-xl group"
            >
              <span>Reservasi</span>
              <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
            </button>
          </div>
        </div>
      </div>

      {/* Decorative Bottom Curve */}
      <div className="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-[#FAF8F5] to-transparent z-10" />
    </div>
  );
}
