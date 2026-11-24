import React from 'react';
import { Sparkles, MapPin, Phone, Mail, ArrowUp, Search, Lock } from 'lucide-react';

export default function Footer({ onOpenLookup }) {
  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  return (
    <footer className="bg-[#122219] text-[#FAF8F5] pt-20 pb-12 border-t border-[#C2A461]/30">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          {/* Column 1: Brand & Identity */}
          <div className="space-y-4">
            <h3 className="font-serif text-2xl font-semibold tracking-[0.2em] text-white">
              THE EAST HOTEL
            </h3>
            <span className="block text-[10px] uppercase tracking-[0.35em] text-[#D8BD7D] font-medium">
              Bali &bull; Luxury Sanctuary
            </span>
            <p className="text-xs text-[#FAF8F5]/70 font-light leading-relaxed pt-2">
              Peristirahatan tebing eksklusif di pesisir timur Bali. Menghadirkan
              keheningan, harmoni alam tropis, dan kemewahan otentik tanpa batas.
            </p>
          </div>

          {/* Column 2: Navigation */}
          <div>
            <h4 className="text-xs uppercase font-bold tracking-[0.2em] text-[#D8BD7D] mb-4">
              Jelajahi Resor
            </h4>
            <ul className="space-y-2.5 text-xs text-[#FAF8F5]/80 font-light">
              <li>
                <a href="#suites-villas" className="hover:text-[#C2A461] transition-colors">
                  Suites & Private Pool Villas
                </a>
              </li>
              <li>
                <a href="#experiences" className="hover:text-[#C2A461] transition-colors">
                  Cliffside Pool & Spa Tradisional
                </a>
              </li>
              <li>
                <a href="#experiences" className="hover:text-[#C2A461] transition-colors">
                  Kuliner Samudra Nusantara
                </a>
              </li>
              <li>
                <a href="#story" className="hover:text-[#C2A461] transition-colors">
                  Filosofi Tri Hita Karana
                </a>
              </li>
              <li>
                <button
                  onClick={onOpenLookup}
                  className="hover:text-[#C2A461] transition-colors text-left flex items-center gap-1.5"
                >
                  <Search className="w-3 h-3 text-[#C2A461]" />
                  Lacak Status Reservasi Anda
                </button>
              </li>
            </ul>
          </div>

          {/* Column 3: Contact & Concierge */}
          <div>
            <h4 className="text-xs uppercase font-bold tracking-[0.2em] text-[#D8BD7D] mb-4">
              Pramutamu & Kontak
            </h4>
            <ul className="space-y-3 text-xs text-[#FAF8F5]/80 font-light">
              <li className="flex items-start gap-2.5">
                <MapPin className="w-4 h-4 text-[#C2A461] shrink-0 mt-0.5" />
                <span>Jl. Pantai Timur Karangasem No. 8, Bali 80871, Indonesia</span>
              </li>
              <li className="flex items-center gap-2.5">
                <Phone className="w-4 h-4 text-[#C2A461] shrink-0" />
                <span>+62 361 8899 000 / +62 812 3456 7890</span>
              </li>
              <li className="flex items-center gap-2.5">
                <Mail className="w-4 h-4 text-[#C2A461] shrink-0" />
                <span>concierge@theeasthotel.com</span>
              </li>
            </ul>
          </div>

          {/* Column 4: Portal & Hours */}
          <div>
            <h4 className="text-xs uppercase font-bold tracking-[0.2em] text-[#D8BD7D] mb-4">
              Layanan Internal
            </h4>
            <p className="text-xs text-[#FAF8F5]/70 font-light mb-4">
              Layanan resepsionis dan pramutamu 24 jam untuk melayani seluruh kebutuhan tamu.
            </p>

            <a
              href="/admin/login"
              target="_blank"
              rel="noreferrer"
              className="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-[#D8BD7D] text-xs font-semibold uppercase tracking-wider border border-white/20 transition-all"
            >
              <Lock className="w-3.5 h-3.5" />
              <span>Portal Admin & Staf</span>
            </a>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-[11px] text-[#FAF8F5]/50 gap-4">
          <div>
            &copy; {new Date().getFullYear()} The East Hotel Bali. Seluruh hak cipta dilindungi.
          </div>
          <button
            onClick={scrollToTop}
            className="flex items-center gap-1.5 text-[#D8BD7D] hover:text-white transition-colors"
          >
            <span>Kembali ke Atas</span>
            <ArrowUp className="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </footer>
  );
}
