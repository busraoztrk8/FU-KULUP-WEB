<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMember;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ClubFormField;
use App\Models\ClubRegistrationData;
use Illuminate\Support\Facades\DB;

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

        // Dinamik form alanlarını validate et
        $formFields = $club->formFields;
        $rules = [];
        foreach ($formFields as $field) {
            $key = 'field_' . $field->id;
            $fieldRules = [];

            if ($field->is_required) {
                if ($field->type === 'checkbox') {
                    $fieldRules[] = 'accepted';
                } else {
                    $fieldRules[] = 'required';
                }
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field->type === 'email') {
                $fieldRules[] = 'email';
            }
            if (in_array($field->type, ['text', 'email'])) {
                // Sayısal alan değilse string kuralı ekle
                $isNumericField = str_contains(strtolower($field->label), 'numara') ||
                                  str_contains(strtolower($field->label), 'no');
                if (!$isNumericField) {
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:500';
                }
            }
            if ($field->type === 'tel') {
                $fieldRules[] = 'digits_between:10,11';
            }
            // Öğrenci numarası gibi "numara" içeren text alanları sayısal olmalı
            if ($field->type === 'text' && (
                str_contains(strtolower($field->label), 'numara') ||
                str_contains(strtolower($field->label), 'no')
            )) {
                $fieldRules[] = 'numeric';
            }
            if ($field->type === 'textarea') {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:2000';
            }

            $rules[$key] = $fieldRules;
        }

        // Alan adlarını Türkçe göster (field_X yerine label)
        $attributes = [];
        foreach ($formFields as $field) {
            $attributes['field_' . $field->id] = $field->label;
        }

        $validated = $request->validate($rules, [], $attributes);

        try {
            return DB::transaction(function () use ($club, $user, $formFields, $request) {
                // Üyelik kaydı oluştur
                $member = ClubMember::create([
                    'club_id' => $club->id,
                    'user_id' => $user->id,
                    'status'  => 'pending',
                    'message' => 'Form ile başvuru yapıldı.',
                ]);

                // Form verilerini kaydet
                foreach ($formFields as $field) {
                    $key = 'field_' . $field->id;
                    $value = $request->input($key);

                    // Checkbox ise "Evet" / "Hayır" olarak kaydet
                    if ($field->type === 'checkbox') {
                        $value = $value ? 'Evet' : 'Hayır';
                    }

                    ClubRegistrationData::create([
                        'club_member_id'      => $member->id,
                        'club_form_field_id'  => $field->id,
                        'value'               => $value,
                    ]);
                }

                // Üye sayısını güncelle
                $club->increment('member_count');

                return back()->with('success', '"' . $club->name . '" kulübüne başvurunuz alındı! Onay bekleniyor.');
            });
        } catch (\Exception $e) {
            \Log::error("Club Registration Error: " . $e->getMessage());
            return back()->withInput()->with('error', 'Başvuru sırasında bir hata oluştu. Lütfen tekrar deneyin.');
        }
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
