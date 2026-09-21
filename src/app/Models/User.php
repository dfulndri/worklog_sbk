<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'avatar_path', 'cover_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function pushNotification(string $title, ?string $message = null, ?string $url = null): Notification
    {
        return $this->notifications()->create([
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ]);
    }

    public function avatarUrl(): string
    {
        return $this->avatar_path
            ? \Illuminate\Support\Facades\Storage::url($this->avatar_path)
            : asset('assets/images/avatar/avatar.jpg');
    }

    public function coverUrl(): string
    {
        return $this->cover_path
            ? \Illuminate\Support\Facades\Storage::url($this->cover_path)
            : asset('assets/images/png/dasher-ui-bootstrap-5.jpg');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
