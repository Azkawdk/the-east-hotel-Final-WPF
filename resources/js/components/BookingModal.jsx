import React, { useState, useEffect } from 'react';
import { X, Calendar, Users, Check, AlertCircle, Sparkles, CreditCard, ArrowRight, ArrowLeft, Copy, CheckCircle2 } from 'lucide-react';

export default function BookingModal({
  isOpen,
  onClose,
  roomTypes,
  initialRoomType,
  initialDates,
  onBookingSuccess,
}) {
  if (!isOpen) return null;

  // Multi-step state: 1 = Dates & Room, 2 = Guest Info, 3 = Payment & Review, 4 = Success
  const [step, setStep] = useState(1);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // Form State
  const today = new Date().toISOString().split('T')[0];
  const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];

  const [formData, setFormData] = useState({
    room_type_id: initialRoomType?.id || roomTypes[0]?.id || '',
    check_in: initialDates?.check_in || today,
    check_out: initialDates?.check_out || tomorrow,
    guests_count: initialDates?.guests || 2,
    guest: {
      full_name: '',
      email: '',
      phone: '',
      country: 'Indonesia',
    },
    special_requests: '',
    payment_method: 'simulation', // 'simulation' or 'pay_at_hotel'
  });

  // Successful booking record
  const [confirmedBooking, setConfirmedBooking] = useState(null);
  const [copiedCode, setCopiedCode] = useState(false);
  const [simulatingPay, setSimulatingPay] = useState(false);
  const [paySuccessMsg, setPaySuccessMsg] = useState(null);

  // Update selected room type if initialRoomType changes
  useEffect(() => {
    if (initialRoomType?.id) {
      setFormData((prev) => ({ ...prev, room_type_id: initialRoomType.id }));
    } else if (!formData.room_type_id && roomTypes.length > 0) {
      setFormData((prev) => ({ ...prev, room_type_id: roomTypes[0].id }));
    }
  }, [initialRoomType, roomTypes]);

  // Selected room object
  const selectedRoom = roomTypes.find((r) => r.id === Number(formData.room_type_id));

  // Calculate nights
  const calculateNights = (inDate, outDate) => {
    if (!inDate || !outDate) return 1;
    const d1 = new Date(inDate);
    const d2 = new Date(outDate);
    const diff = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
    return diff > 0 ? diff : 1;
  };

  const nights = calculateNights(formData.check_in, formData.check_out);
  const basePrice = selectedRoom?.base_price || 0;
  const subtotal = basePrice * nights;
  const serviceAmount = Math.round(subtotal * 0.1);
  const taxAmount = Math.round((subtotal + serviceAmount) * 0.11);
  const totalAmount = subtotal + serviceAmount + taxAmount;

  const formatIDR = (val) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(val || 0);
  };

  // Submit Booking
  const handleSubmitBooking = async () => {
    setError(null);
    setLoading(true);

    try {
      const payload = {
        room_type_id: Number(formData.room_type_id),
        check_in: formData.check_in,
        check_out: formData.check_out,
        guests_count: Number(formData.guests_count),
        guest: {
          full_name: formData.guest.full_name.trim(),
          email: formData.guest.email.trim(),
          phone: formData.guest.phone.trim(),
          country: formData.guest.country ? formData.guest.country.trim() : null,
        },
        special_requests: formData.special_requests ? formData.special_requests.trim() : null,
        payment_method: formData.payment_method,
      };

      const res = await fetch('/api/v1/reservations', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const data = await res.json();

      if (!res.ok) {
        if (data.errors) {
          const firstErr = Object.values(data.errors)[0];
          throw new Error(Array.isArray(firstErr) ? firstErr[0] : String(firstErr));
        }
        throw new Error(data.message || 'Gagal membuat reservasi. Silakan periksa tanggal.');
      }

      setConfirmedBooking(data);
      setStep(4);
      if (onBookingSuccess) onBookingSuccess(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  // Instant Payment Simulation
  const handleSimulatePayment = async () => {
    if (!confirmedBooking) return;
    setSimulatingPay(true);
    setError(null);

    try {
      const res = await fetch(`/api/v1/reservations/${confirmedBooking.code}/payments`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({
          email: confirmedBooking.guest?.email,
          method: 'simulation',
          outcome: 'success',
        }),
      });

      const updated = await res.json();

      if (!res.ok) {
        throw new Error(updated.message || 'Gagal memproses pembayaran');
      }

      setConfirmedBooking(updated);
      setPaySuccessMsg('Pembayaran simulasi berhasil! Status reservasi kini Lunas & Dikonfirmasi.');
      if (onBookingSuccess) onBookingSuccess(updated);
    } catch (err) {
      setError(err.message);
    } finally {
      setSimulatingPay(false);
    }
  };

  const handleCopyCode = () => {
    if (!confirmedBooking?.code) return;
    navigator.clipboard.writeText(confirmedBooking.code);
    setCopiedCode(true);
    setTimeout(() => setCopiedCode(false), 2500);
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

      {/* Modal Card */}
      <div className="relative bg-[#FAF8F5] rounded-3xl max-w-2xl w-full max-h-[92vh] overflow-y-auto shadow-2xl border border-[#E8DFC8] z-10 flex flex-col text-[#26221F]">
        {/* Header */}
        <div className="p-6 border-b border-[#E8DFC8] flex items-center justify-between bg-white rounded-t-3xl sticky top-0 z-20">
          <div>
            <span className="text-[10px] uppercase tracking-[0.25em] font-semibold text-[#A68742]">
              Sistem Reservasi Tamu
            </span>
            <h2 className="font-serif text-2xl font-normal text-[#122219]">
              {step === 4 ? 'Reservasi Berhasil' : 'Pemesanan Kamar & Vila'}
            </h2>
          </div>
          <button
            onClick={onClose}
            className="w-9 h-9 rounded-full bg-[#FAF8F5] hover:bg-[#EFE8DC] text-[#122219] flex items-center justify-center transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Stepper (Only on Steps 1-3) */}
        {step < 4 && (
          <div className="bg-[#FAF8F5] px-6 py-3 border-b border-[#E8DFC8]/60 flex items-center justify-between text-xs font-medium">
            <div className={`flex items-center gap-1.5 ${step === 1 ? 'text-[#122219] font-bold' : 'text-[#827566]'}`}>
              <span className={`w-5 h-5 rounded-full flex items-center justify-center text-[10px] ${step === 1 ? 'bg-[#C2A461] text-white' : 'bg-[#E8DFC8] text-[#54483C]'}`}>1</span>
              <span>Kamar & Waktu</span>
            </div>
            <div className="w-8 h-[1px] bg-[#E8DFC8]" />
            <div className={`flex items-center gap-1.5 ${step === 2 ? 'text-[#122219] font-bold' : 'text-[#827566]'}`}>
              <span className={`w-5 h-5 rounded-full flex items-center justify-center text-[10px] ${step === 2 ? 'bg-[#C2A461] text-white' : 'bg-[#E8DFC8] text-[#54483C]'}`}>2</span>
              <span>Data Tamu</span>
            </div>
            <div className="w-8 h-[1px] bg-[#E8DFC8]" />
            <div className={`flex items-center gap-1.5 ${step === 3 ? 'text-[#122219] font-bold' : 'text-[#827566]'}`}>
              <span className={`w-5 h-5 rounded-full flex items-center justify-center text-[10px] ${step === 3 ? 'bg-[#C2A461] text-white' : 'bg-[#E8DFC8] text-[#54483C]'}`}>3</span>
              <span>Pembayaran</span>
            </div>
          </div>
        )}

        {/* Modal Content */}
        <div className="p-6 sm:p-8 flex-1 space-y-6">
          {error && (
            <div className="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-start gap-2.5">
              <AlertCircle className="w-4 h-4 shrink-0 text-red-600 mt-0.5" />
              <span>{error}</span>
            </div>
          )}

          {/* STEP 1: Room & Stay Dates */}
          {step === 1 && (
            <div className="space-y-5">
              {/* Room Selection */}
              <div>
                <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-2">
                  Pilih Tipe Kamar / Vila <span className="text-red-500">*</span>
                </label>
                <select
                  value={formData.room_type_id}
                  onChange={(e) => setFormData({ ...formData, room_type_id: e.target.value })}
                  className="w-full p-3.5 bg-white border border-[#E8DFC8] rounded-xl text-sm font-semibold text-[#122219] outline-none focus:border-[#C2A461] shadow-sm"
                >
                  {roomTypes.map((r) => (
                    <option key={r.id} value={r.id}>
                      {r.name} — {formatIDR(r.base_price)}/malam (Maks. {r.capacity} Tamu)
                    </option>
                  ))}
                </select>
              </div>

              {/* Dates Grid */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="bg-white p-3.5 rounded-xl border border-[#E8DFC8]">
                  <label className="block text-[10px] uppercase font-bold tracking-wider text-[#827566] mb-1">
                    Tanggal Check-in
                  </label>
                  <input
                    type="date"
                    min={today}
                    value={formData.check_in}
                    onChange={(e) => setFormData({ ...formData, check_in: e.target.value })}
                    className="w-full text-xs font-semibold text-[#122219] outline-none bg-transparent"
                    required
                  />
                </div>
                <div className="bg-white p-3.5 rounded-xl border border-[#E8DFC8]">
                  <label className="block text-[10px] uppercase font-bold tracking-wider text-[#827566] mb-1">
                    Tanggal Check-out
                  </label>
                  <input
                    type="date"
                    min={formData.check_in || today}
                    value={formData.check_out}
                    onChange={(e) => setFormData({ ...formData, check_out: e.target.value })}
                    className="w-full text-xs font-semibold text-[#122219] outline-none bg-transparent"
                    required
                  />
                </div>
              </div>

              {/* Guest Count */}
              <div>
                <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-2">
                  Jumlah Tamu
                </label>
                <select
                  value={formData.guests_count}
                  onChange={(e) => setFormData({ ...formData, guests_count: Number(e.target.value) })}
                  className="w-full p-3 bg-white border border-[#E8DFC8] rounded-xl text-xs font-semibold text-[#122219] outline-none focus:border-[#C2A461]"
                >
                  {[1, 2, 3, 4, 5, 6].map((n) => (
                    <option key={n} value={n}>
                      {n} Orang {selectedRoom && n > selectedRoom.capacity ? `(Melebihi kapasitas ideal ${selectedRoom.capacity})` : ''}
                    </option>
                  ))}
                </select>
              </div>

              {/* Quick Summary Card */}
              {selectedRoom && (
                <div className="p-4 rounded-2xl bg-white border border-[#E8DFC8]/70 flex items-center justify-between">
                  <div>
                    <span className="text-[10px] uppercase tracking-wider text-[#827566]">
                      Estimasi Menginap
                    </span>
                    <div className="text-sm font-semibold text-[#122219]">
                      {nights} Malam &bull; {selectedRoom.name}
                    </div>
                  </div>
                  <div className="text-right">
                    <span className="text-[10px] uppercase tracking-wider text-[#827566]">
                      Subtotal Kamar
                    </span>
                    <div className="font-serif text-lg font-bold text-[#A68742]">
                      {formatIDR(subtotal)}
                    </div>
                  </div>
                </div>
              )}
            </div>
          )}

          {/* STEP 2: Guest Information */}
          {step === 2 && (
            <div className="space-y-4">
              <div>
                <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-1">
                  Nama Lengkap (Sesuai KTP / Paspor) <span className="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  placeholder="Contoh: Gusti Made Aryana"
                  value={formData.guest.full_name}
                  onChange={(e) =>
                    setFormData({
                      ...formData,
                      guest: { ...formData.guest, full_name: e.target.value },
                    })
                  }
                  className="w-full p-3 bg-white border border-[#E8DFC8] rounded-xl text-xs font-semibold text-[#122219] outline-none focus:border-[#C2A461]"
                  required
                />
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-1">
                    Email Kontak <span className="text-red-500">*</span>
                  </label>
                  <input
                    type="email"
                    placeholder="nama@email.com"
                    value={formData.guest.email}
                    onChange={(e) =>
                      setFormData({
                        ...formData,
                        guest: { ...formData.guest, email: e.target.value },
                      })
                    }
                    className="w-full p-3 bg-white border border-[#E8DFC8] rounded-xl text-xs font-semibold text-[#122219] outline-none focus:border-[#C2A461]"
                    required
                  />
                </div>
                <div>
                  <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-1">
                    Nomor WhatsApp / HP <span className="text-red-500">*</span>
                  </label>
                  <input
                    type="tel"
                    placeholder="+62 812 xxxx xxxx"
                    value={formData.guest.phone}
                    onChange={(e) =>
                      setFormData({
                        ...formData,
                        guest: { ...formData.guest, phone: e.target.value },
                      })
                    }
                    className="w-full p-3 bg-white border border-[#E8DFC8] rounded-xl text-xs font-semibold text-[#122219] outline-none focus:border-[#C2A461]"
                    required
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-1">
                  Negara Asal
                </label>
                <input
                  type="text"
                  value={formData.guest.country}
                  onChange={(e) =>
                    setFormData({
                      ...formData,
                      guest: { ...formData.guest, country: e.target.value },
                    })
                  }
                  className="w-full p-3 bg-white border border-[#E8DFC8] rounded-xl text-xs font-semibold text-[#122219] outline-none focus:border-[#C2A461]"
                />
              </div>

              <div>
                <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-1">
                  Permintaan Khusus (Opsional)
                </label>
                <textarea
                  rows={2}
                  placeholder="Contoh: Pengaturan bulan madu, lantai tinggi, penjemputan bandara, dsb."
                  value={formData.special_requests}
                  onChange={(e) => setFormData({ ...formData, special_requests: e.target.value })}
                  className="w-full p-3 bg-white border border-[#E8DFC8] rounded-xl text-xs text-[#122219] outline-none focus:border-[#C2A461]"
                />
              </div>
            </div>
          )}

          {/* STEP 3: Payment Method & Financial Breakdown */}
          {step === 3 && (
            <div className="space-y-6">
              {/* Financial Calculation Breakdown */}
              <div className="bg-white p-5 rounded-2xl border border-[#E8DFC8] space-y-3">
                <h3 className="text-xs uppercase font-bold tracking-[0.2em] text-[#122219] border-b border-[#E8DFC8]/60 pb-2">
                  Rincian Biaya Resmi Reservasi
                </h3>
                <div className="flex justify-between text-xs text-[#54483C]">
                  <span>
                    {selectedRoom?.name} ({formatIDR(basePrice)} &times; {nights} malam)
                  </span>
                  <span className="font-semibold">{formatIDR(subtotal)}</span>
                </div>
                <div className="flex justify-between text-xs text-[#54483C]">
                  <span>Biaya Layanan Resor (10%)</span>
                  <span className="font-semibold">{formatIDR(serviceAmount)}</span>
                </div>
                <div className="flex justify-between text-xs text-[#54483C]">
                  <span>Pajak Pertambahan Nilai (PPN 11%)</span>
                  <span className="font-semibold">{formatIDR(taxAmount)}</span>
                </div>
                <div className="pt-2 border-t border-[#E8DFC8] flex justify-between items-center text-sm font-bold text-[#122219]">
                  <span className="font-serif text-base">Total Tagihan Bersih</span>
                  <span className="font-serif text-xl text-[#A68742]">
                    {formatIDR(totalAmount)}
                  </span>
                </div>
              </div>

              {/* Payment Method Selector */}
              <div>
                <label className="block text-xs uppercase font-bold tracking-wider text-[#827566] mb-2">
                  Metode Pembayaran
                </label>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <label
                    className={`p-4 rounded-2xl border cursor-pointer transition-all flex items-start gap-3 ${
                      formData.payment_method === 'simulation'
                        ? 'border-[#C2A461] bg-[#C2A461]/10 text-[#122219]'
                        : 'border-[#E8DFC8] bg-white text-[#54483C]'
                    }`}
                  >
                    <input
                      type="radio"
                      name="payment_method"
                      value="simulation"
                      checked={formData.payment_method === 'simulation'}
                      onChange={() => setFormData({ ...formData, payment_method: 'simulation' })}
                      className="mt-1"
                    />
                    <div>
                      <div className="text-xs font-bold text-[#122219]">
                        Bayar Online (Simulasi Instan)
                      </div>
                      <div className="text-[11px] text-[#827566] font-light mt-0.5">
                        Simulasi pembayaran lunas instan dengan kode unik.
                      </div>
                    </div>
                  </label>

                  <label
                    className={`p-4 rounded-2xl border cursor-pointer transition-all flex items-start gap-3 ${
                      formData.payment_method === 'pay_at_hotel'
                        ? 'border-[#C2A461] bg-[#C2A461]/10 text-[#122219]'
                        : 'border-[#E8DFC8] bg-white text-[#54483C]'
                    }`}
                  >
                    <input
                      type="radio"
                      name="payment_method"
                      value="pay_at_hotel"
                      checked={formData.payment_method === 'pay_at_hotel'}
                      onChange={() => setFormData({ ...formData, payment_method: 'pay_at_hotel' })}
                      className="mt-1"
                    />
                    <div>
                      <div className="text-xs font-bold text-[#122219]">
                        Bayar di Hotel (Check-in)
                      </div>
                      <div className="text-[11px] text-[#827566] font-light mt-0.5">
                        Konfirmasi instan, pembayaran diselesaikan saat tiba di hotel.
                      </div>
                    </div>
                  </label>
                </div>
              </div>
            </div>
          )}

          {/* STEP 4: Success Ticket Card */}
          {step === 4 && confirmedBooking && (
            <div className="space-y-6 text-center py-2 animate-fadeIn">
              <div className="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-md">
                <CheckCircle2 className="w-9 h-9" />
              </div>

              <div>
                <h3 className="font-serif text-2xl font-bold text-[#122219] mb-1">
                  Reservasi Anda Diterima
                </h3>
                <p className="text-xs text-[#827566]">
                  Rincian konfirmasi telah dikirimkan ke email{' '}
                  <strong className="text-[#122219]">{confirmedBooking.guest?.email}</strong>
                </p>
              </div>

              {paySuccessMsg && (
                <div className="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
                  {paySuccessMsg}
                </div>
              )}

              {/* Booking Code Card */}
              <div className="bg-white rounded-2xl p-6 border-2 border-dashed border-[#C2A461] max-w-md mx-auto shadow-sm">
                <span className="text-[10px] uppercase font-bold tracking-[0.25em] text-[#827566]">
                  Kode Unik Booking Tamu
                </span>
                <div className="font-mono text-3xl font-extrabold text-[#122219] tracking-wider my-2">
                  {confirmedBooking.code}
                </div>
                <button
                  onClick={handleCopyCode}
                  className="inline-flex items-center gap-1.5 text-xs text-[#A68742] hover:text-[#C2A461] font-semibold"
                >
                  <Copy className="w-3.5 h-3.5" />
                  {copiedCode ? 'Tersalin ke Clipboard!' : 'Salin Kode Booking'}
                </button>
              </div>

              {/* Summary Details */}
              <div className="bg-white p-5 rounded-2xl border border-[#E8DFC8] text-left text-xs space-y-2 max-w-md mx-auto">
                <div className="flex justify-between py-1 border-b border-[#E8DFC8]/50">
                  <span className="text-[#827566]">Tamu</span>
                  <span className="font-semibold text-[#122219]">{confirmedBooking.guest?.full_name}</span>
                </div>
                <div className="flex justify-between py-1 border-b border-[#E8DFC8]/50">
                  <span className="text-[#827566]">Check-in / Check-out</span>
                  <span className="font-semibold text-[#122219]">
                    {confirmedBooking.check_in} &rarr; {confirmedBooking.check_out}
                  </span>
                </div>
                <div className="flex justify-between py-1 border-b border-[#E8DFC8]/50">
                  <span className="text-[#827566]">Total Tagihan</span>
                  <span className="font-bold text-[#A68742]">{formatIDR(confirmedBooking.total_amount)}</span>
                </div>
                <div className="flex justify-between py-1 border-b border-[#E8DFC8]/50">
                  <span className="text-[#827566]">Status Reservasi</span>
                  <span className="font-bold uppercase text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                    {confirmedBooking.status}
                  </span>
                </div>
                <div className="flex justify-between py-1">
                  <span className="text-[#827566]">Status Pembayaran</span>
                  <span className={`font-bold uppercase px-2 py-0.5 rounded ${confirmedBooking.payment?.status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'}`}>
                    {confirmedBooking.payment?.status || 'Pending'}
                  </span>
                </div>
              </div>

              {/* Instant Simulation Pay Button if Pending */}
              {confirmedBooking.payment?.status !== 'paid' && (
                <div className="pt-2">
                  <button
                    onClick={handleSimulatePayment}
                    disabled={simulatingPay}
                    className="px-6 py-3 rounded-full text-xs font-semibold uppercase tracking-wider text-white bg-emerald-700 hover:bg-emerald-800 transition-all shadow-md flex items-center justify-center gap-2 mx-auto disabled:opacity-50"
                  >
                    <CreditCard className="w-4 h-4" />
                    <span>{simulatingPay ? 'Memproses...' : 'Simulasikan Pembayaran Lunas Sekarang'}</span>
                  </button>
                </div>
              )}
            </div>
          )}
        </div>

        {/* Modal Footer Controls */}
        <div className="p-6 border-t border-[#E8DFC8] bg-white rounded-b-3xl flex items-center justify-between">
          {step > 1 && step < 4 && (
            <button
              onClick={() => setStep(step - 1)}
              className="px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-[#54483C] bg-[#FAF8F5] hover:bg-[#EFE8DC] rounded-xl flex items-center gap-1.5 transition-colors"
            >
              <ArrowLeft className="w-3.5 h-3.5" />
              Kembali
            </button>
          )}

          {step === 1 && (
            <div className="ml-auto">
              <button
                onClick={() => setStep(2)}
                className="px-8 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-[#122219] bg-[#C2A461] hover:bg-[#D8BD7D] rounded-full transition-all shadow-md flex items-center gap-2"
              >
                <span>Lanjut Isi Data</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </button>
            </div>
          )}

          {step === 2 && (
            <button
              onClick={() => {
                if (!formData.guest.full_name || !formData.guest.email || !formData.guest.phone) {
                  setError('Mohon lengkapi Nama, Email, dan Nomor Telepon.');
                  return;
                }
                setError(null);
                setStep(3);
              }}
              className="ml-auto px-8 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-[#122219] bg-[#C2A461] hover:bg-[#D8BD7D] rounded-full transition-all shadow-md flex items-center gap-2"
            >
              <span>Lanjut ke Biaya</span>
              <ArrowRight className="w-3.5 h-3.5" />
            </button>
          )}

          {step === 3 && (
            <button
              onClick={handleSubmitBooking}
              disabled={loading}
              className="ml-auto px-8 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white bg-[#122219] hover:bg-[#1A3326] rounded-full transition-all shadow-lg flex items-center gap-2 disabled:opacity-50"
            >
              <span>{loading ? 'Memproses...' : 'Konfirmasi & Buat Booking'}</span>
              <Check className="w-3.5 h-3.5" />
            </button>
          )}

          {step === 4 && (
            <button
              onClick={onClose}
              className="w-full py-3 text-xs font-semibold uppercase tracking-[0.2em] text-[#122219] bg-[#C2A461] hover:bg-[#D8BD7D] rounded-full transition-all shadow"
            >
              Selesai & Tutup
            </button>
          )}
        </div>
      </div>
    </div>
  );
}
