# 17-08-28 | 24. Gün

Bugün IPv4 ve IPv6 dan bahsedeceğiz.
DHCP ve DNS server kuracağız.

Sanal olarak aşağıdaki sanal makinelere ihtiyacımız olacak.

```
2  Windows10 x64
2  Windows Server 2012 R2
```

SRV1'e domain-controller kurulurken, SRV2'ye additional-domain-controller kurulacak.
SRV2'ye DNS kurulmayacak.

![alt text](https://github.com/acsariyildiz/sistem4/blob/gh-pages/images/activeD2.png?raw=true "Logo Title Text 1")
  
* Additional Domain controllerı kurmadan önce lütfen domaine alın.
* DHCP tehlikelidir. Çünkü iyi ayarlanmazsa birden fazla ip dağıtıcısı cihazlara aynı ip verebilir.
* Cİhazlara statik ip verilmesi fazla efor gerektirebilir, hata yapılmasına neden olabilir.
* Active Directory'nin DHCP veritabanının doğruluğunu sağlamak çok daha kolaydır.
* DHCP'nin ip dağıtmasında iki aşama var. Generation ve Renewal.

```
Generation : İlk ip alma süreci.
Renewal : IP'nin yenilenme süreci.
```

* `!!!!!!!!!!! Slayttan anlatılıyor..`
* IP almak isteyen client bir broadcast ile DHCP dağıtıcısı arar. `DHCPDISCOVER`
* Bu broadcastler layer-4'de UDP kullanır. TCP kullanmaz.
* 8 günlük bir bekleme süresi vardır. Bu sürenin yarısında 4. günde anlaşma tazelenir.
* 7. günde %87.5 da haber alınamazsa bu IP boşa düşer. 
* 8 gün sonunda başka adrese berilebilir.
* `!!!!!!!!! Uygulama yapılıyor...`
* SRV1 içerisinde `add roles and features`'dan DHCP ekleyin.
* `role based`, `next`, `next` buradaki ayarlar değişmiyor.
* DHCP sunucusunu aşağıdaki gibi kurup 

![alt text](https://github.com/acsariyildiz/sistem4/blob/gh-pages/images/17-08-28-1.png?raw=true "Logo Title Text 1")
![alt text](https://github.com/acsariyildiz/sistem4/blob/gh-pages/images/17-08-28-1.png?raw=true "Logo Title Text 1")


```
Server
Firewall
Router
Switch
IP Telefon
```

* Bu cihazlar statik IP alması gereken cihazlar. Çünkü değişirse ulaşımda sıkıntı çekebiliriz.
Bu durumda sabit IP'li cihazlar için bir blok ayırmamız gerekiyor. 

* Örneğin ip aralığımız. `192.168.8.1 - 102.168.8.254`
* Bu durumda örneğin `192.168.8.1 - 102.168.8.50` havuzunu vermeyeceğiz. (150-254) 

```
Manual 
%80     %20

Failover DHCP 
%50    %50
```
* Manual 
  * SRV1 içerisinde ip blogunu scope'u %80 e göre yapacaktınız.Manual
  * SRV2 içerisinde ipleri %20 e göre yapacaktınız.
  
  * SRV Configure Failover 
 

* İkinci örnek olarak failover DHCP kullanacağız.
* Failover'da ortak database'i kullanırlar.