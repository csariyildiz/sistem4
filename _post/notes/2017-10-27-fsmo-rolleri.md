---
layout: post
title:  "FSMO Rolleri"
categories: second
tags: fsmo active-directory
---


* Komut satırından `netdom query fsmo` yazılarak roller görülebilir. FSMO (Flexible Single Master Operations) rollerin genel adıdır.

![FSMO Rolleri](https://github.com/acsariyildiz/sistem4/blob/gh-pages/images/fsm0.png?raw=true "FSMO Rolleri")

* 5 mühür Domain Controllerda master rol dediğimiz rollerdir. İki ayrı seviyede vardır. (Forest ve Domain)

* En önemlileri `Schema Master` ve `Domain Naming Master` forest seviyesindedir. Diğerleri domain seviyesindedir. 

* Örneğin bir Child Domain Controller diğer üçüne sahipken `Schema Master` ve `Domain Naming Master`a sahip değildir.

* Forest en az bir domainden oluşan bir yapıdır. 
Forest kurulurken bir domain kurulur ve bu domaine root domain ismi verilir. 
Root domainde 5 master rol vardır. 
Fakat child ve tree domain kontrollerda domain seviyesinde roller vardır.

* `Schema`, Domain nasıl bir obje olduğunun tanımı şemadadır.
Client nasıl bir objedir o da şemadadır. 
Şemada tanımlı bir makina ancak domaine alınabilir. 
Schema Master rol en önemli roldür.

* Domain Naming Master ve Schema Master roller en önemlileridir. 

* `PDC`, Kimlik denetiminden (authentication) sorumludur. Örneğin login esnasında client üzerindeki bilginin doğruluğu
için RID Master ile beraber çalışır. Bir obje eklendiğinde her objeye bir SID atanır.

* `Infrastructure` Site Yapısı, Zarar görürse gidilecek yol bulunamaz. 

* Bu rollerin tek bir domain controllerda olması pek önerilmez.
Çünkü server kaybedildiğinde hepsi birden gider.
Additional DC'ye direk olarak geçmez önce dağıtmamız gerekir.
Additional DC'de DC'nin rol kopyaları `NTDS` klasöründe bulunur.


## FSMO Rollerinin Taşınması

* Bunu göstermek için DC1'i silindi. Kral öldü yerine yerine yeni kral geçiyor gibi düşünebiliriz.


```
Active Directory Users And Computers | dsa.msc
  domainadi.local >> sağ click >> operation master. 
```

Domain seviyesindeki rollerin hangi
domain controllerda olduğunu aktarır. İlk domain kontroller gittiğinden açılması gecikiyor. Aynı sebepten Error veriyor.
Açılan pencereden RID, PDC ve Infrastructure rolleri görebiliyoruz.

![FSMO Rolleri](https://github.com/acsariyildiz/sistem4/blob/gh-pages/images/fsm2.png?raw=true "FSMO Rolleri")

* `change` ile force ile kaybolan DC'den transfer ediyoruz. 

* Rolleri nasıl görüntüleyip change ediyoruz? Henüz grafik arayüzden. Ama sonraki süreçte komutla yapacağız.

* nslookup gibi domain yapısına sorgu gönderebiliriz.

```
netdom query fsmo
```

* Burada bazı rolleri alamadığımızı görüyoruz.

```
Active Directory Domains and Trusts
  domainadi.local >> sağ click >> operation master...
```


![Active Directory Domains And Trusts](https://github.com/acsariyildiz/sistem4/blob/gh-pages/images/fsm1.png?raw=true "FSMO Rolleri Operational Master")

* Buradan yalnız domain naming master'ı görüntüleyebilirim. Schema hala görüntülenemedi. Change inaktif bunu görüyoruz.

* En önemli rolümüz Schema Master Schema, Bu konsolu mmc'ye ekleyeceğiz. Bütün konsollar system32'nin altındadır. Boş bir mmc kullanarak
çağırmak mümkündür. Gelen ekran system32 klasörünü okur çok daha fazla konsolu görüntüler. Fakat ihtiyacımız olacak konsol güvenlik amacıyla gizlenmiştir.
Öncelikle bu konsolu ortaya çıkarmak için:

```
cmd içerisine:
regsvr32 schmmgmt.dll
```

* Böylece modül register edildi.

* `Sağ click >> operation master...` Change şimdi geldi. Fakat bunu `nsdsutil` kullanarak yapacağız.

## nsdsutil ile FSMO Rollerinin Taşınması

* `ntdsutil` cmd içerisinde bir programdır. AD işlemlerini yapar. Cisco'ya benzer. `?` ile görüntülemek mümkündür.
* Rolleri buradan transfer edeceğiz.

```
FSMO ######################
transfer / seize ##########

ntdsutil
  roles
  connections
    connect to server dc2
    quit
  transfer/seize pdc
  transfer/seize rid master
  transfer/seize infrastructure master
  transfer/seize naming master
  transfer/seize schema master
  quit
quit

```

* `roles` diyerek bir alt komuta girdik. `?` ile görüntüle. `transfer` ile master rolleri aktarabileceğimizi gördük. Fakat DC olmadığından işimize yaramaz. İkisinin de online olması gerekiyor. `Seize` ise bunu zorlayarak yapıyoruz. İkisi online olsa da aktarır fakat gerek yoktu olarak uyarır. Fakat önce DC'ye bağlanmamız lazım. 

* Sırası ile `connections`, `connect to server serveradi-dc2`, `quit` komutları.
* Ardından `seize` ile rolleri geçiriyoruz.

```
seize pdc
seize rid master
seize infrastructure master
seize naming master
seize schema master
```

* DNS server da gitti. Bunun yerine DNS server kurulması gerekiyor. Bu işlemlerden sonra `netdom` ile hepsinin geçtiğini görüyoruz.
Artık additional domain controller diyemeyiz root domain controller oldu. 

## Silinen DC İçin Düzeltmeler

* `Users And Computers`da hala görünüyor. `Sites And Services`de Servers'de görünüyor. Sürekli replike etmeye çalışacak.
Alttan yukarı temizlemeye bağlayacaksınız. Önce NTDS settings sonra kendisi. Users and computers'da da kontrol ediyoruz kalkmış.

* DNS içerisinde `_tcp`yi kontrol ediyoruz. 8 adetti silinmiş. `_udp`de aynı şekilde. `domainadi.local` properties'inde name server tab'ında gözüküyor. Kaldırıp apply ediyoruz.

* Aynısı reverse lookup zone için de geçerli. Unknown yazıyor zaten. 

* Forwarders da aynı şekilde. Bilgisayarın DNS adresini de düzeltiyoruz. Client cihazlarda yine DNS adresi düzeltildi.

* Active directory en az bir site'dan oluşur aynı site'da olunca replike eder. DC1 ve DC2 aynı side'da olduğu için birbirini replike eder.
