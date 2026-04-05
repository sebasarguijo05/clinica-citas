<?php

namespace App\Services;

use App\Models\User;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

class GoogleCalendarService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->addScope(Calendar::CALENDAR_EVENTS);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    // Generar URL de autorización
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    // Intercambiar código por token
    public function getTokenFromCode(string $code): array
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        return $token;
    }

    // Configurar cliente con token del usuario
    public function setUserToken(User $user): bool
    {
        if (!$user->google_token) {
            return false;
        }

        $token = json_decode($user->google_token, true);
        $this->client->setAccessToken($token);

        // Refrescar token si expiró
        if ($this->client->isAccessTokenExpired()) {
            if ($user->google_refresh_token) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                $user->update(['google_token' => json_encode($newToken)]);
                $this->client->setAccessToken($newToken);
            } else {
                return false;
            }
        }

        return true;
    }

    // Crear evento en Google Calendar
    public function createEvent(User $user, array $eventData): ?string
    {
        if (!$this->setUserToken($user)) {
            return null;
        }

        $service = new Calendar($this->client);

        $event = new Event([
            'summary'     => $eventData['title'],
            'description' => $eventData['description'] ?? '',
            'start' => new EventDateTime([
                'dateTime' => $eventData['start'],
                'timeZone' => 'America/Tegucigalpa',
            ]),
            'end' => new EventDateTime([
                'dateTime' => $eventData['end'],
                'timeZone' => 'America/Tegucigalpa',
            ]),
            'reminders' => [
                'useDefault' => false,
                'overrides'  => [
                    ['method' => 'email',  'minutes' => 24 * 60],
                    ['method' => 'popup',  'minutes' => 60],
                ],
            ],
        ]);

        $createdEvent = $service->events->insert('primary', $event);
        return $createdEvent->getId();
    }

    // Eliminar evento de Google Calendar
    public function deleteEvent(User $user, string $eventId): void
    {
        if (!$this->setUserToken($user)) {
            return;
        }

        $service = new Calendar($this->client);

        try {
            $service->events->delete('primary', $eventId);
        } catch (\Exception $e) {
            // Si el evento no existe, ignoramos el error
        }
    }
}