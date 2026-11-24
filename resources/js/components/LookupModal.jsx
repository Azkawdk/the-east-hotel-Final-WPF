import React, { useState } from 'react';
import { X, Search, Calendar, Users, AlertCircle, CheckCircle2, CreditCard, Copy, RefreshCw } from 'lucide-react';

export default function LookupModal({ isOpen, onClose }) {
  if (!isOpen) return null;

  const [code, setCode] = useState('');
  const [email, setEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [booking, setBooking] = useState(null);

  const [simulatingPay, setSimulatingPay] = useState(false);
  const [paySuccessMsg, setPaySuccessMsg] = useState(null);

  const formatIDR = (val) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(val || 0);
  };

  const handleLookup = async (e) => {
    if (e) e.preventDefault();
    setError(null);
    setBooking(null);
    setPaySuccessMsg(null);
    setLoading(true);

    try {
      const url = `/api/v1/reservations/lookup?code=${encodeURIComponent(code.trim().toUpperCase())}&email=${encodeURIComponent(email.trim().toLowerCase())}`;
      const res = await fetch(url, {
        headers: { Accept: 'application/json' },
      });
      const data = await res.json();

      if (!res.ok) {
        throw new Error(data.message || 'Reservasi tidak ditemukan. Pastikan Kode dan Email benar.');
      }

      setBooking(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleSimulatePayment = async () => {
    if (!booking) return;
    setSimulatingPay(true);
    setError(null);

    try {
      const res = await fetch(`/api/v1/reservations/${booking.code}/payments`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({
          email: booking.guest?.email,
          method: 'simulation',
          outcome: 'success',
        }),
      });

      const updated = await res.json();
      if (!res.ok) {
        throw new Error(updated.message || 'Gagal memproses pembayaran');
      }

      setBooking(updated);
      setPaySuccessMsg('Pembayaran simulasi berhasil! Status reservasi sekarang LUNAS.');
    } catch (err) {
      setError(err.message);
    } finally {
      setSimulatingPay(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 md:p-8 animate-fadeIn">
      {/* Backdrop */}
      <div
        className="fixed inset-0 bg-[#122219]/75 backdrop-blur-sm transition-opacity"
        onClick={() => {
          if (!loading) onClose();
        }}
      />

      {/* Modal Dialog */}
      <div className="relative bg-[#FAF8F5] rounded-3xl max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-[#E8DFC8] z-10 flex flex-col text-[#26221F]">
        {/* Header */}
        <div className="p-6 border-b border-[#E8DFC8] flex items-center justify-between bg-white rounded-t-3xl">
          <div>
            <span className="text-[10px] uppercase tracking-[0.25em] font-semibold text-[#A68742]">
              Layanan Tamu
            </span>
            <h2 className="font-serif text-2xl font-normal text-[#122219]">
              Lacak Status Reservasi
            </h2>
          </div>
          <button
            onClick={onClose}
            className="w-9 h-9 rounded-full bg-[#FAF8F5] hover:bg-[#EFE8DC] text-[#122219] flex items-center justify-center transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Body */}
        <div className="p-6 sm:p-8 space-y-6 flex-1">
          {/* Lookup Form */}
          <form onSubmit={handleLookup} className="space-y-4">
            <div>
              <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-1">
                Kode Booking (Contoh: TEH-XXXXXX)
              </label>
              <input
                type="text"
                placeholder="TEH-ALSLYF"
                value={code}
                onChange={(e) => setCode(e.target.value.toUpperCase())}
                className="w-full p-3.5 bg-white border border-[#E8DFC8] rounded-xl text-sm font-mono font-bold tracking-wider text-[#122219] uppercase outline-none focus:border-[#C2A461]"
                required
              />
            </div>

            <div>
              <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-1">
                Email Tamu Pemesan
              </label>
              <input
                type="email"
                placeholder="email@example.com"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full p-3.5 bg-white border border-[#E8DFC8] rounded-xl text-sm text-[#122219] outline-none focus:border-[#C2A461]"
                required
              />
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#122219] bg-[#C2A461] hover:bg-[#D8BD7D] rounded-full transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-50"
            >
              <Search className="w-4 h-4" />
              <span>{loading ? 'Mencari Data...' : 'Cek Status Reservasi'}</span>
            </button>
          </form>

          {/* Error Message */}
          {error && (
            <div className="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-start gap-2.5">
              <AlertCircle className="w-4 h-4 shrink-0 text-red-600 mt-0.5" />
              <span>{error}</span>
            </div>
          )}

          {paySuccessMsg && (
            <div className="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
              {paySuccessMsg}
            </div>
          )}

          {/* Booking Found Card */}
          {booking && (
            <div className="bg-white rounded-2xl p-6 border border-[#E8DFC8] shadow-sm space-y-4 animate-fadeIn">
              <div className="flex items-center justify-between border-b border-[#E8DFC8]/70 pb-3">
                <div>
                  <span className="text-[10px] text-[#827566] uppercase tracking-wider block">
                    Kode Reservasi
                  </span>
                  <span className="font-mono text-xl font-bold text-[#122219]">
                    {booking.code}
                  </span>
                </div>
                <div className="text-right">
                  <span
                    className={`inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                      booking.status === 'confirmed' || booking.status === 'checked_in'
                        ? 'bg-emerald-100 text-emerald-800'
                        : booking.status === 'cancelled'
                        ? 'bg-red-100 text-red-800'
                        : 'bg-amber-100 text-amber-800'
                    }`}
                  >
                    {booking.status}
                  </span>
                </div>
              </div>

              {/* Detail Items */}
              <div className="space-y-2 text-xs">
                <div className="flex justify-between py-1 border-b border-[#FAF8F5]">
                  <span className="text-[#827566]">Nama Tamu</span>
                  <span className="font-semibold text-[#122219]">{booking.guest?.full_name}</span>
                </div>
                <div className="flex justify-between py-1 border-b border-[#FAF8F5]">
                  <span className="text-[#827566]">Kamar Diberikan</span>
                  <span className="font-semibold text-[#122219]">
                    {booking.details?.[0]?.room?.room_number ? `No. ${booking.details[0].room.room_number}` : 'Alokasi saat check-in'} &bull; {booking.details?.[0]?.room_type?.name}
                  </span>
                </div>
                <div className="flex justify-between py-1 border-b border-[#FAF8F5]">
                  <span className="text-[#827566]">Periode Menginap</span>
                  <span className="font-semibold text-[#122219]">
                    {booking.check_in} &rarr; {booking.check_out} ({booking.nights} malam)
                  </span>
                </div>
                <div className="flex justify-between py-1 border-b border-[#FAF8F5]">
                  <span className="text-[#827566]">Total Tagihan</span>
                  <span className="font-bold text-[#A68742] text-sm">{formatIDR(booking.total_amount)}</span>
                </div>
                <div className="flex justify-between py-1">
                  <span className="text-[#827566]">Status Pembayaran</span>
                  <span
                    className={`font-bold uppercase px-2 py-0.5 rounded text-[10px] ${
                      booking.payment?.status === 'paid'
                        ? 'bg-emerald-50 text-emerald-700'
                        : 'bg-amber-50 text-amber-700'
                    }`}
                  >
                    {booking.payment?.status || 'Pending'}
                  </span>
                </div>
              </div>

              {/* Instant Simulation Pay Button if Pending */}
              {booking.payment?.status !== 'paid' && booking.status !== 'cancelled' && (
                <div className="pt-3 border-t border-[#E8DFC8]">
                  <button
                    onClick={handleSimulatePayment}
                    disabled={simulatingPay}
                    className="w-full py-3 rounded-full text-xs font-semibold uppercase tracking-wider text-white bg-emerald-700 hover:bg-emerald-800 transition-all shadow flex items-center justify-center gap-2 disabled:opacity-50"
                  >
                    <CreditCard className="w-4 h-4" />
                    <span>{simulatingPay ? 'Memproses...' : 'Simulasikan Pembayaran Lunas Sekarang'}</span>
                  </button>
                </div>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
