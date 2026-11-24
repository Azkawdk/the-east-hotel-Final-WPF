import React, { useState, useEffect } from 'react';
import { Calendar, Search, Menu, X, Compass, ShieldCheck } from 'lucide-react';

export default function Navbar({ onOpenBooking, onOpenLookup }) {
  const [scrolled, setScrolled] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 40);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const navLinks = [
    { label: 'Suites & Villas', href: '#suites-villas' },
    { label: 'Pengalaman & Spa', href: '#experiences' },
    { label: 'Filosofi Resor', href: '#story' },
  ];

  return (
    <header
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
        scrolled
          ? 'glass-nav py-3 shadow-[0_4px_25px_rgba(38,34,31,0.06)] border-b border-[#E8DFC8]'
          : 'bg-gradient-to-b from-black/60 via-black/20 to-transparent py-5 text-white'
      }`}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between">
          {/* Brand Logo */}
          <a href="#" className="flex flex-col group text-left">
            <span
              className={`font-serif tracking-[0.25em] text-xl sm:text-2xl font-semibold transition-colors ${
                scrolled ? 'text-[#122219]' : 'text-white'
              }`}
            >
              THE EAST HOTEL
            </span>
            <span
              className={`text-[9px] uppercase tracking-[0.35em] font-medium transition-colors ${
                scrolled ? 'text-[#A68742]' : 'text-[#D8BD7D]'
              }`}
            >
              Bali &bull; Luxury Sanctuary
            </span>
          </a>

          {/* Desktop Nav Links */}
          <nav className="hidden md:flex items-center space-x-8">
            {navLinks.map((link) => (
              <a
                key={link.label}
                href={link.href}
                className={`text-xs uppercase tracking-[0.18em] font-medium transition-all duration-300 hover:text-[#C2A461] ${
                  scrolled ? 'text-[#3E3832]' : 'text-white/90'
                }`}
              >
                {link.label}
              </a>
            ))}

            {/* Lacak Reservasi Trigger */}
            <button
              onClick={onOpenLookup}
              className={`flex items-center gap-1.5 text-xs uppercase tracking-[0.18em] font-medium transition-colors ${
                scrolled
                  ? 'text-[#6A6054] hover:text-[#C2A461]'
                  : 'text-white/80 hover:text-white'
              }`}
            >
              <Search className="w-3.5 h-3.5 text-[#C2A461]" />
              Lacak Booking
            </button>
          </nav>

          {/* Desktop Actions */}
          <div className="hidden md:flex items-center space-x-4">
            <a
              href="/admin/login"
              target="_blank"
              rel="noreferrer"
              className={`text-[11px] uppercase tracking-[0.15em] font-medium px-3 py-1.5 rounded transition-all ${
                scrolled
                  ? 'text-[#827566] hover:text-[#122219]'
                  : 'text-white/60 hover:text-white'
              }`}
              title="Portal Staf & Admin"
            >
              Staf Admin
            </a>

            <button
              onClick={onOpenBooking}
              className="relative inline-flex items-center justify-center px-6 py-2.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#122219] bg-[#C2A461] hover:bg-[#D8BD7D] rounded-full transition-all duration-300 shadow-[0_4px_18px_rgba(194,164,97,0.35)] hover:shadow-[0_6px_22px_rgba(194,164,97,0.5)] transform hover:-translate-y-0.5 active:translate-y-0"
            >
              <Calendar className="w-3.5 h-3.5 mr-2" />
              Reservasi
            </button>
          </div>

          {/* Mobile Menu Button */}
          <div className="flex md:hidden items-center gap-2">
            <button
              onClick={onOpenBooking}
              className="px-3.5 py-1.5 text-[11px] font-semibold uppercase tracking-wider text-[#122219] bg-[#C2A461] rounded-full shadow"
            >
              Booking
            </button>
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className={`p-2 rounded-lg transition-colors ${
                scrolled ? 'text-[#122219]' : 'text-white'
              }`}
              aria-label="Toggle Navigation"
            >
              {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Drawer */}
      {mobileMenuOpen && (
        <div className="md:hidden glass-nav border-b border-[#E8DFC8] px-6 py-6 animate-fadeIn text-[#26221F]">
          <div className="flex flex-col space-y-4">
            {navLinks.map((link) => (
              <a
                key={link.label}
                href={link.href}
                onClick={() => setMobileMenuOpen(false)}
                className="text-sm uppercase tracking-widest font-serif text-[#122219] py-1 border-b border-[#E8DFC8]/40"
              >
                {link.label}
              </a>
            ))}
            <button
              onClick={() => {
                setMobileMenuOpen(false);
                onOpenLookup();
              }}
              className="flex items-center gap-2 text-sm uppercase tracking-widest font-serif text-[#A68742] py-1 text-left"
            >
              <Search className="w-4 h-4" />
              Lacak Status Reservasi
            </button>
            <div className="pt-2 flex flex-col gap-2">
              <button
                onClick={() => {
                  setMobileMenuOpen(false);
                  onOpenBooking();
                }}
                className="w-full py-3 text-xs font-semibold uppercase tracking-widest text-[#122219] bg-[#C2A461] rounded-full shadow text-center"
              >
                Buat Reservasi Baru
              </button>
              <a
                href="/admin/login"
                className="text-center text-xs text-[#827566] tracking-wider py-1"
              >
                Masuk Portal Admin
              </a>
            </div>
          </div>
        </div>
      )}
    </header>
  );
}
