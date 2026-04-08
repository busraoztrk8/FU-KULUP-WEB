<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMember;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KayitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── KULÜP KAYIT ──────────────────────────────────────────

    public function kulupKayit(Request $request, Club $club)
    {
        $user = Auth::user();

        // Zaten üye mi?
        $existing = ClubMember::where('club_id', $club->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Bu kulübe zaten başvurdunuz. Durum: ' . $this->statusLabel($existing->status));
        }

        $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        ClubMember::create([
            'club_id' => $club->id,
            'user_id' => $user->id,
            'status'  => 'pending',
            'message' => $request->message,
        ]);

        // Üye sayısını güncelle (pending dahil)
        $club->increment('member_count');

        return back()->with('success', '"' . $club->name . '" kulübüne başvurunuz alındı! Onay bekleniyor.');
    }

    public function kulupAyril(Club $club)
    {
        $deleted = ClubMember::where('club_id', $club->id)
            ->where('user_id', Auth::id())
            ->delete();

        if ($deleted) {
            $club->decrement('member_count');
            return back()->with('success', 'Kulüpten ayrıldınız.');
        }

        return back()->with('error', 'Zaten bu kulübün üyesi değilsiniz.');
    }

    // ── ETKİNLİK KAYIT ───────────────────────────────────────

    public function etkinlikKayit(Request $request, Event $event)
    {
        $user = Auth::user();

        if ($event->status !== 'published') {
            return back()->with('error', 'Bu etkinliğe kayıt yapılamaz.');
        }

        // 1. Kulübe üyelik kontrolü/kaydı
        $clubMember = ClubMember::where('club_id', $event->club_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$clubMember) {
            ClubMember::create([
                'club_id' => $event->club_id,
                'user_id' => $user->id,
                'status'  => 'pending',
                'message' => $request->note ? 'Etkinlik kaydı sırasında otomatik oluşturuldu. Not: ' . $request->note : 'Etkinlik kaydı sırasında otomatik oluşturuldu.',
            ]);
            $event->club->increment('member_count');
        }

        // 2. Etkinlik kaydı
        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                $existing->update(['status' => 'registered']);
                $event->increment('current_participants');
                return back()->with('success', 'Etkinliğe ve kulübe başarıyla kayıt oldunuz!');
            }
            return back()->with('info', 'Bu etkinliğe zaten kayıtlısınız.');
        }

        $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id'  => $user->id,
            'status'   => 'registered',
            'note'     => $request->note,
        ]);

        $event->increment('current_participants');

        return back()->with('success', '"' . $event->title . '" etkinliğine ve ' . $event->club->name . ' kulübüne başarıyla kayıt oldunuz!');
    }

    public function etkinlikIptal(Event $event)
    {
        $reg = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', 'registered')
            ->first();

        if ($reg) {
            $reg->update(['status' => 'cancelled']);
            $event->decrement('current_participants');
            return back()->with('success', 'Etkinlik kaydınız iptal edildi.');
        }

        return back()->with('error', 'Bu etkinliğe kayıtlı değilsiniz.');
    }

    private function statusLabel(string $status): string
    {
        return match($status) {
            'pending'  => 'Onay Bekleniyor',
            'approved' => 'Onaylandı',
            'rejected' => 'Reddedildi',
            default    => $status,
        };
    }
}
