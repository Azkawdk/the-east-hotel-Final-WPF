import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import Navbar from './components/Navbar';
import Hero from './components/Hero';
import RoomTypesSection from './components/RoomTypesSection';
import ExperiencesSection from './components/ExperiencesSection';
import StorySection from './components/StorySection';
import BookingModal from './components/BookingModal';
import LookupModal from './components/LookupModal';
import RoomDetailModal from './components/RoomDetailModal';
import Footer from './components/Footer';

export default function App() {
  const [roomTypes, setRoomTypes] = useState([]);
  const [amenities, setAmenities] = useState([]);
  const [loading, setLoading] = useState(true);

  // Filter & Search State
  const [searchParams, setSearchParams] = useState({
    check_in: '',
    check_out: '',
    guests: 2,
  });

  // Modals state
  const [isBookingOpen, setIsBookingOpen] = useState(false);
  const [isLookupOpen, setIsLookupOpen] = useState(false);
  const [selectedRoomType, setSelectedRoomType] = useState(null);
  const [detailRoomType, setDetailRoomType] = useState(null);

  // Fetch initial data
  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [roomsRes, amenRes] = await Promise.all([
        fetch('/api/v1/room-types').then((r) => r.json()),
        fetch('/api/v1/amenities').then((r) => r.json()),
      ]);
      setRoomTypes(Array.isArray(roomsRes) ? roomsRes : []);
      setAmenities(Array.isArray(amenRes) ? amenRes : []);
    } catch (err) {
      console.error('Failed fetching data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleOpenBooking = (roomType = null) => {
    setSelectedRoomType(roomType);
    setIsBookingOpen(true);
  };

  const handleOpenDetail = (roomType) => {
    setDetailRoomType(roomType);
  };

  return (
    <div className="min-h-screen bg-[#FAF8F5] text-[#26221F] selection:bg-[#C2A461] selection:text-white font-sans">
      {/* Floating Luxury Navbar */}
      <Navbar 
        onOpenBooking={() => handleOpenBooking()} 
        onOpenLookup={() => setIsLookupOpen(true)} 
      />

      {/* Hero Header Section */}
      <Hero 
        searchParams={searchParams}
        setSearchParams={setSearchParams}
        onSearch={() => {
          const el = document.getElementById('suites-villas');
          if (el) el.scrollIntoView({ behavior: 'smooth' });
        }}
        onBookNow={() => handleOpenBooking()}
      />

      {/* Suites & Villas Section (CRUD Read & Book) */}
      <section id="suites-villas">
        <RoomTypesSection 
          roomTypes={roomTypes} 
          loading={loading}
          onSelectRoom={(type) => handleOpenBooking(type)}
          onViewDetail={(type) => handleOpenDetail(type)}
        />
      </section>

      {/* Resort Amenities & Experiences */}
      <section id="experiences">
        <ExperiencesSection amenities={amenities} />
      </section>

      {/* Sanctuary Story & Balinese Heritage */}
      <section id="story">
        <StorySection onBookNow={() => handleOpenBooking()} />
      </section>

      {/* Footer */}
      <Footer onOpenLookup={() => setIsLookupOpen(true)} />

      {/* Booking Modal (CRUD Create Reservation & Payment) */}
      <BookingModal
        isOpen={isBookingOpen}
        onClose={() => setIsBookingOpen(false)}
        roomTypes={roomTypes}
        initialRoomType={selectedRoomType}
        initialDates={searchParams}
        onBookingSuccess={() => fetchData()}
      />

      {/* Lookup Modal (Lacak Reservasi Tamu & Bayar) */}
      <LookupModal
        isOpen={isLookupOpen}
        onClose={() => setIsLookupOpen(false)}
      />

      {/* Room Detail Modal */}
      <RoomDetailModal
        roomType={detailRoomType}
        isOpen={!!detailRoomType}
        onClose={() => setDetailRoomType(null)}
        onBookThis={(type) => {
          setDetailRoomType(null);
          handleOpenBooking(type);
        }}
      />
    </div>
  );
}

const rootEl = document.getElementById('app');
if (rootEl) {
  createRoot(rootEl).render(<App />);
}
