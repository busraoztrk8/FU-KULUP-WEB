# Laravel CAS Kimlik Doğrulama Paketi

Bu paket, Laravel uygulamalarında CAS (Merkezi Kimlik Doğrulama Servisi) kimlik doğrulamasını kolayca uygulamanızı
sağlar.

## Kurulum
Paketi kuracağınız projenin composer.json kısmına aşağıdaki bloğu yapıştırıp ardından yükleme komutunu çalıştırınız. <br>

```php
"repositories": [
        {
            "type": "vcs",
            "url": "https://ddogitlab.firat.edu.tr/firat_packages/laravel_cas_package.git"
        }
    ]
```

Paketi composer ile kurabilirsiniz:
```bash
composer require ddyo/cas-authenticate
```

Readme dosyasını publish etmek için :
```bash
php artisan vendor:publish --tag=ddyo-cas-readme
```

## Temel Kullanım

### Response Değerleri

| Değişken Adı                  | Açıklama                                         | Veri Türü  |
|--------------------------------|-------------------------------------------------|------------|
| **userUID**                    | Kullanıcının TC kimlik numarası                 | `string`   |
| **userDescription**            | Kullanıcı tipi (Örn: AKADEMİK PERSONEL)         | `string`   |
| **userFullName**               | Kullanıcının tam adı ve kimlik numarası         | `string`   |
| **userFirstName**              | Kullanıcının adı                                | `string`   |
| **userLastName**               | Kullanıcının soyadı                             | `string`   |
| **userEMailAddress**           | Kullanıcının e-posta adresi                     | `string`   |
| **userTitle**                  | Kullanıcının unvanı (Örn: ARAŞTIRMA GÖREVLİSİ)  | `string`   |
| **userTelephoneNumber**        | Kullanıcının telefon numarası                   | `string`   |
| **userLogonNamePreWindows2000**| Kullanıcının kısa oturum açma adı               | `string`   |
| **userDistinguishedName**      | Kullanıcının dizin içindeki ayırt edici adı (DN)| `string`   |
| **userLogonName**              | Kullanıcının oturum açma adı                    | `string`   |
| **userOfficeLocation**         | Kullanıcının ofis/birim bilgisi                 | `string`   |
| **userGroupMembership**        | Kullanıcının üye olduğu gruplar                 | `array`    |
| **userGender**                 | Kullanıcının cinsiyeti                          | `string`   |

### Temel Migration ve Model Attributeleri

```php
//Migration
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('userUID')->nullable();
    $table->string('userDescription')->nullable();
    $table->string('userFullName')->nullable();
    $table->string('userFirstName')->nullable();
    $table->string('userLastName')->nullable();
    $table->string('userEMailAddress')->unique()->nullable();
    $table->string('userTitle')->nullable();
    $table->string('userTelephoneNumber')->nullable();
    $table->string('userLogonNamePreWindows2000')->nullable();
    $table->string('userDistinguishedName')->nullable();
    $table->string('userLogonName')->nullable();
    $table->string('userOfficeLocation')->nullable();
    $table->string('userGender')->nullable();
    $table->softDeletes();
    $table->timestamp('created_at')->useCurrent()->nullable();
    $table->timestamp('updated_at')->useCurrent()->nullable();
});

//Model
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    // use HasRoles;  //Laravel Permission (Spatie) kullanımı için

    protected $fillable = [
        'userUID',
        'userDescription',
        'userFullName',
        'userFirstName',
        'userLastName',
        'userEMailAddress',
        'userTitle',
        'userTelephoneNumber',
        'userLogonNamePreWindows2000',
        'userDistinguishedName',
        'userLogonName',
        'userOfficeLocation',
        'userGender',
    ];

    protected $hidden = [
       'userUID',
       'userTelephoneNumber'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}

```

### Facade Kullanımı

```php
use DDYO\CasAuthenticate\Facades\CasAuth;


$attributes = CasAuth::login();
if (count($attributes)>0){
    // işlemlere devam edilir ...
}

//use Illuminate\Http\Request;
function logout(Request $request){

    Auth::logout(); // Laravel'in kendi Auth user'ını temizler
    
    $request->session()->invalidate(); // Session verilerini geçersiz kılar
    $request->session()->regenerateToken(); // CSRF token'ını yeniler
    
    // Belirli bir route'a yönlendirme ile çıkış
    CasAuth::logout(["service" => route("dashboard.index")]);
    
    // Belirli bir URL'ye yönlendirme ile çıkış
    CasAuth::logout(["service" => "https://mveysiguler.com/tr"]);
}
```

**Önemli Uyarı:** Eğer `logout` metodunu kullanırken `DDYO\CasAuthenticate\CasPhp\CAS_OutOfSequenceBeforeClientException` hatası alırsanız veya yönlendirme gerçekleşmeyip `boş ekran` gelirse, ikinci parametre olarak initialization işlemini `false` olarak ayarlayın, varsayılan **_`true`_** olarak çalışır:

```php
CasAuth::logout(["service" => route("dashboard.index")], false);
```
## Özel Kullanıcı İşleme

Kullanıcıların nasıl oluşturulacağını veya güncelleneceğini middleware içerisinde, `authenticateAndLogin` metoduna bir
callback fonksiyonu geçirerek özelleştirebilirsiniz:

```php
$result = CasAuth::authenticateAndLogin(function($attributes) {
    // Kullanıcı oluşturma/güncelleme için özel mantığınız
    //geriye user nesnesi dönmeli --> return $user;
});
```

## Tavsiye Edilen Kullanım

`authenticateAndLogin` metodunu kullanarak işlemlere devam etmeniz önerilmektedir, herhangi bir custom edilme
senaryosunda kodunuzu genişleterek kullanabilirsinz.

```php
function handle(....){
    if (!Auth::check()) {
        $attr = CasAuth::authenticateAndLogin();
    
        if ($attr) {
            $user_attr = $attr['attributes'];
            $user = $attr['user'];
            Auth::login($user);
        } else {
            abort(401,"KULLANICI CAS BİLGİSİ BULUNAMADI");
        }
    }
    
    if (auth()->user()->getRoleNames()->count() > 0) {
        return $next($request);
    } else {
        abort(401,"KULLANICI ROL YETKİSİ BULUNAMADI");
    }
}
```

### `authenticateAndLogin` Varsayılan Çalışma Yapısı

`CasAuth::authenticateAndLogin();` şeklinde çağırıldığında aşağıdaki kod otomatik olarak çalışır
```php
 if (isset($attributes['userEMailAddress'])) {
    $attr = $attributes;
    $user = User::where('userEMailAddress', $attr['userEMailAddress'])->first();
    if ($user) {
        $user->update($attr);
    } else {
        if(User::where('userEMailAddress', $attr['userEMailAddress'])->withTrashed()->first() && User::where('userEMailAddress', $attr['userEMailAddress'])->withTrashed()->first()->deleted_at != null){
            $user = User::where('userEMailAddress', $attr['userEMailAddress'])->withTrashed()->first();
            $user->deleted_at = null;
            $user->save();
        }else{
            $user = User::create($attr);
        }
    }
} else {
    throw new \Exception('Cas verisinde userEMailAddress alanı yok');
}
```
#### İşleyiş Mantığı
- **Eğer `userEMailAddress` alanı mevcutsa**:
    1. **Kullanıcı Veritabanında Aranır**:
        - `userEMailAddress` alanı kullanılarak, ilgili kullanıcının veritabanında olup olmadığı kontrol edilir.
    2. **Eğer Kullanıcı Mevcutsa**:
        - Kullanıcının bilgileri gelen **CAS verileriyle güncellenir**.
    3. **Eğer Kullanıcı Mevcut Değilse**:
        - **Silinmiş Kullanıcı Kontrolü Yapılır**:
            - Eğer kullanıcı daha önce **silinmişse** (`deleted_at` alanı doluysa), kullanıcının `deleted_at` değeri `null` yapılarak **geri yüklenir**.
        - **Yeni Kullanıcı Oluşturulur**:
            - Kullanıcı daha önce hiç oluşturulmamışsa, **yeni bir kayıt** eklenir.

- **Eğer `userEMailAddress` alanı CAS verisinde yoksa**, sistem bir hata fırlatır (`Exception`).

#### Önemli Notlar
- **Soft Delete Kullanımı**:
    - Kullanıcı tablosunda **soft delete**  mekanizması kullanılmalıdır.
    - `deleted_at` alanı `null` yapılarak, daha önce silinmiş kullanıcılar geri yüklenebilir.
    - User modelinde **Soft Deletes** özelliği etkin olmalıdır. (`use SoftDeletes;` kullanılmalıdır.)



## Lisans

MIT Lisansı (MIT). Daha fazla bilgi için [Lisans Dosyası](LICENSE)'na bakın. 
