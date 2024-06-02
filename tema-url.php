<?php
/*
Plugin Name: Özel Tema URL Yönlendirme Eklentisi
Plugin URI: https://www.jrodix.com
Description: Tema URL Yönlendirme Eklentisi Masaüstü Mobil ve AMP
Version: 1.0
Author: Ömer ATABER - OmerAti 
Author URI: https://www.jrodix.com
*/

if (!class_exists('CustomThemeSwitcher')) {
    class CustomThemeSwitcher
    {
        public function __construct()
        {
            add_action('admin_menu', array($this, 'admin_sayfa_olustur'));
            add_action('admin_init', array($this, 'ayar_kaydet'));
            add_action('init', array($this, 'tema_degistir'), 1);
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        }

        public function admin_sayfa_olustur()
        {
            add_menu_page(
                'Tema URL Ayarları',
                'Tema URL Ayarları',
                'manage_options',
                'ozel-tema-degistirici',
                array($this, 'sayfa_icerigi'),
                'dashicons-admin-generic',
                100
            );
        }

       public function ayar_kaydet()
{
    register_setting('tema_degistirici_ayarlar', 'masaustu_tema');
    register_setting('tema_degistirici_ayarlar', 'amp_tema_url');
    register_setting('tema_degistirici_ayarlar', 'cache_aktif');
    register_setting('tema_degistirici_ayarlar', 'yonlendirme_hizli');
    register_setting('tema_degistirici_ayarlar', 'amp_sayfa_masaustune_gizle');
    register_setting('tema_degistirici_ayarlar', 'iframe_aktif'); // Yeni ayar
}

public function sayfa_icerigi()
{
    ?>
    <div class="wrap">
        <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']) : ?>
            <div id="message" class="updated notice is-dismissible">
                <p>Ayarlar başarıyla kaydedildi!</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Bu mesajı kapat</span></button>
            </div>
        <?php endif; ?>
        <div style="float:left; width: 70%;">
            <h1><img src="<?php echo plugin_dir_url(__FILE__) . 'logo.png'; ?>" alt="Logo" style="height: 40px; vertical-align: middle;"> Tema URL Ayarları</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('tema_degistirici_ayarlar');
                do_settings_sections('tema_degistirici_ayarlar');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Masaüstü Teması</th>
                        <td>
                            <select name="masaustu_tema">
                                <?php
                                $temalar = wp_get_themes();
                                $secili_masaustu_tema = get_option('masaustu_tema');
                                foreach ($temalar as $tema_slug => $tema) {
                                    echo '<option value="' . esc_attr($tema_slug) . '" ' . selected($secili_masaustu_tema, $tema_slug, false) . '>' . esc_html($tema->get('Name')) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">AMP veya Mobil Tema URL</th>
                        <td>
                            <input type="text" name="amp_tema_url" value="<?php echo esc_attr(get_option('amp_tema_url')); ?>" />
                            <p class="description">AMP veya Mobil temasının URL'sini girin. Örneğin: https://example.com/amp</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Cache</th>
                        <td>
                            <input type="checkbox" name="cache_aktif" <?php checked(get_option('cache_aktif'), 'on'); ?> />
                            <label>Aktif</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Hızlı Yönlendirme</th>
                        <td>
                            <input type="checkbox" name="yonlendirme_hizli" <?php checked(get_option('yonlendirme_hizli'), 'on'); ?> />
                            <label>Aktif</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Masaüstünde Varsayılan Tema</th>
                        <td>
                            <input type="checkbox" name="amp_sayfa_masaustune_gizle" <?php checked(get_option('amp_sayfa_masaustune_gizle'), 'on'); ?> />
                            <label>Aktif</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">URL'yi iframe'de Göster</th>
                        <td>
                            <input type="checkbox" name="iframe_aktif" <?php checked(get_option('iframe_aktif'), 'on'); ?> />
                            <label>Aktif</label>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Ayarları Kaydet'); ?>
            </form>
        </div>
        <div style="float:right; width: 30%;">
            <div class="bilgiler">
                <h2>Bilgilendirme</h2>
                <ul>
                    <li>Wordpress için Gerekli Modüller:</li>
                    <ul>
                        <li>Web Sunucusu: <?php echo server_info_check(); ?></li>
                        <li>Disk Alanı: <?php echo disk_space_check(); ?></li>
                        <li>PHP Sürümü: <?php echo php_version_check(); ?></li>
                        <li>Veritabanı: <?php echo database_check(); ?></li>
                        <li>RAM: <?php echo ram_check(); ?></li>
                        <li>HTTPS Desteği: <?php echo https_support_check(); ?></li>
                    </ul>
                </ul>
            </div>
        </div>
        <div style="clear:both;"></div>
        <div class="footer-text">JRodix.Com Internet Hizmetleri Tarafından Kodlanmış ve Yayınlanmıştır</div>
    </div>
    <aside class="amp-bilgilendirme">
        <h2>Nasıl AMP Sayfası Oluşturulur?</h2>
        <p>AMP (Accelerated Mobile Pages), web sitelerinin mobil cihazlarda hızlı bir şekilde yüklenmesini sağlayan açık kaynak kodlu bir projesidir. AMP sayfaları, hızlı bir kullanıcı deneyimi sağlamak için optimize edilmiştir.</p>
        <p>Aşağıdaki adımları izleyerek kendi AMP sayfanızı oluşturabilirsiniz:</p>
        <ol>
            <li>HTML belgenizin başlangıcına <code>&lt;!DOCTYPE html&gt;</code> ekleyin.</li>
            <li><code>&lt;html&gt;</code> etiketine <code>amp</code> özelliği ekleyin: <code>&lt;html ⚡ lang="tr"&gt;</code>.</li>
            <li><code>&lt;head&gt;</code> bölümüne AMP için gerekli meta etiketlerini ekleyin: viewport, charset, canonical, vb.</li>
            <li><code>&lt;style amp-custom&gt;</code> etiketi içine özel CSS kodlarınızı ekleyin.</li>
            <li>Sayfanızın içeriğini oluşturun ve AMP uyumlu bileşenler kullanın.</li>
            <li>Gerektiğinde AMP JS kitaplığını yükleyin: <code>&lt;script async src="https://cdn.ampproject.org/v0.js"&gt;&lt;/script&gt;</code>.</li>
            <li>Örnek bir AMP sayfası oluşturun:</li>
        </ol>
        <div class="amp-html-example">
            <textarea id="ampHtmlCode" readonly>&lt;!DOCTYPE html&gt;
&lt;html ⚡ lang=&quot;tr&quot;&gt;
&lt;head&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
    &lt;title&gt;Örnek AMP Sayfası&lt;/title&gt;
    &lt;link rel=&quot;canonical&quot; href=&quot;https://example.com/amp.html&quot;&gt;
    &lt;meta name=&quot;viewport&quot; content=&quot;width=device-width,minimum-scale=1,initial-scale=1&quot;&gt;
    &lt;style amp-custom&gt;
        /* Özel CSS Kodları */
    &lt;/style&gt;
    &lt;script async src=&quot;https://cdn.ampproject.org/v0.js&quot;&gt;&lt;/script&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;p&gt;Örnek AMP Sayfası&lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
            <button onclick="copyAmpHtmlCode()">Kopyala</button>
        </div>
        <p id="copyStatusMessage"></p>
    </aside>
    <script>
        function copyAmpHtmlCode() {
            var ampHtmlCode = document.getElementById("ampHtmlCode");
            ampHtmlCode.select();
            document.execCommand("copy");
            var copyStatusMessage = document.getElementById("copyStatusMessage");
            copyStatusMessage.textContent = "AMP HTML kodu kopyalandı!";
        }
    </script>
    <?php
}


public function tema_degistir()
{
    if (is_admin() || is_customize_preview()) return;

    $masaustu_tema = get_option('masaustu_tema');
    $amp_tema_url = get_option('amp_tema_url');
    $amp_sayfa_masaustune_gizle = get_option('amp_sayfa_masaustune_gizle');
    $iframe_aktif = get_option('iframe_aktif');

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $bot_agents = array(
        'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider', 'YandexBot', 'Sogou', 'Exabot', 'facebot', 'ia_archiver'
    );

    foreach ($bot_agents as $bot_agent) {
        if (strpos($user_agent, $bot_agent) !== false) {
            if (!empty($masaustu_tema) && wp_get_theme($masaustu_tema)->exists() && !is_child_theme($masaustu_tema)) {
                $current_theme = wp_get_theme();
                $active_theme = $current_theme->get_stylesheet();

                if ($masaustu_tema !== $active_theme) {
                    switch_theme($masaustu_tema, $masaustu_tema);
                }
            }
            return;
        }
    }

    if (wp_is_mobile() && $amp_tema_url && get_option('yonlendirme_hizli') && !isset($_GET['no_redirect'])) {
        if (!$amp_sayfa_masaustune_gizle || ($amp_sayfa_masaustune_gizle && $amp_tema_url)) {
            if ($iframe_aktif) {
                echo '<!DOCTYPE html>
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <style>
                        html, body {
                            margin: 0;
                            padding: 0;
                            height: 100%;
                            overflow: hidden;
                        }
                        iframe {
                            border: none;
                            width: 100%;
                            height: 100%;
                            position: fixed;
                            top: 0;
                            left: 0;
                        }
                    </style>
                </head>
                <body>
                    <iframe src="' . esc_url($amp_tema_url) . '"></iframe>
                </body>
                </html>';
                exit;
            } else {
                wp_redirect($amp_tema_url);
                exit;
            }
        }
    }

    if (!empty($masaustu_tema) && wp_get_theme($masaustu_tema)->exists() && !is_child_theme($masaustu_tema) && !get_option('amp_sayfa_masaustune_gizle')) {
        $current_theme = wp_get_theme();
        $active_theme = $current_theme->get_stylesheet();

        if ($masaustu_tema !== $active_theme) {
            switch_theme($masaustu_tema, $masaustu_tema);
        }
    }
}

public function enqueue_admin_styles()
{
wp_enqueue_style('custom-admin-style', plugin_dir_url(__FILE__) . 'admin-style.css');
}
}
new CustomThemeSwitcher();
include_once(plugin_dir_path(__FILE__) . 'checks.php');
}
