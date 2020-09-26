---
layout: post
title:  "Dynamic Routing Protokolleri, RIP"
categories: second
tags: dynamic-routing rip
---

## Dynamic Routing Protokollerinin Sınıflandırılması
1. Distance Vector Protokoller (RIP, IGRP)
2. Link State Protokoller (OSPF)
3. Hybrid Protokoller (EIGRP)

* **Distance Vector Protokoller** `routing table update` mantığıyla çalısırlar.
Yani belirli zaman aralıklarında sahip oldukları network bilgilerini komsu routerlarına gönderirler.
Aynı şekilde komsu routerlarından da aynı bilgileri alırlar. 
Bu döngünün sonunda her router sistemdeki bütün networkler öğrenmis olur ve uygun yol seçimini yapar.

* **Link State Protokoller** ise sürekli bir update yapmak yerine, 
komsu routerlarının `up` olup olmadıklarını anlamak için küçük `Hello` paketleri gönderirler. 
Sadece gerektigi zamanlarda, yeni bir router ortama eklendiğinde veya bir router down olduğunda, 
sadece o bilgi ile ilgili update gerçeklestirirler.

* **Hybrid Protokoller** hem `Distance Vector` hem de `Link State` protokollerin bazı özelliklerini tasır. 
Bu gruba üye olan `EIGRP` Cisco tarafından ortaya çıkarılmıstır ve sadece Cisco routerlarda çalısır.

## RIP

* Rip (Routing Information Protocol) en iyi yol seçimi yaparken tek kriter olarak hop sayısına bakar. 
* Rip tanımlanarak olusturulmus bir networkte maksimum hop sayısı 15’ dir.
* `16.` hop’ tan sonra Destination Unreachable hatası verecektir.
* Rip ile tanımlanan routerlar her 30 saniyede bir kendisinde tanımlı olan networkleri komsu routerlarına iletirler.
  * Burada dikkat edilmesi gereken bir konu, RIP ile tanımlanan bir networkün bağlı bulunduğu interface’ i, aynı zaman da routing update gönderilecek bir interface olarak seçiyor olmamızdır.
* Konfigürasyon sırasında subnet mask girilemez ve subnet masklar update sırasında ip adresinin sınıfına ait subnet mask seçilerek gönderilir.
* Rip konfigürasyonu diğer bürün routing protokollerde olduğu gibi oldukça basittir. (Bütün subnet masklar 255.255.255.0)

![RIP 1](https://github.com/acsariyildiz/Notes/raw/master/Network/img/r1.png)
--------------------------------------------------------------------------------------------
![RIP 2](https://github.com/acsariyildiz/Notes/raw/master/Network/img/r2.png)
--------------------------------------------------------------------------------------------

* Burada 120 Rip protokol için Administrative Distinct denen ve routing protokoller arasında ki önceliği belirleyen değerdir. Diğer ifade da “n” gibi bir sayıdır (burada 1) ve hedef networke ulaşmak için asılacak hop sayısıdır

--------------------------------------------------------------------------------------------
![RIP 3](https://github.com/acsariyildiz/Notes/raw/master/Network/img/r3.png)
--------------------------------------------------------------------------------------------

## Router'larda Performans İçin Timer Kullanımı

* **Route Update Timer:** Router’ın komsularına, yönlendirme tablosunun tümünü göndermesi için beklediği zaman aralığı. Tipik olarak 30 sn.’dir.

* **Route Invalid Timer:** Bir yönlendirmenin, yönlendirme tablosunda geçersiz olarak kabul edilmesi için geçmesi gereken zaman aralığı. 
90 sn.’lik bu zaman aralığında yönlendirme tablosundaki bir yönlendirme kaydıyla alakalı bir güncelleme olmazsa o kayıt geçersiz olarak isaretlenir. 
Ardından komsu router’lara bu yönlendirmenin geçersiz olduğu bildirilir.

* **Route Flush Timer:** Bir yönlendirmenin geçersiz olması ve yönlendirme tablosundan kaldırılması için gereken zaman aralığı(240 sn.).

* **Route Update Timer:** Router’ın komsularına, yönlendirme tablosunun tümünü göndermesi için beklediği zaman aralığı. Tipik olarak 30 sn.’dir.

* **Route Invalid Timer:** Bir yönlendirmenin, yönlendirme tablosunda geçersiz olarak kabul edilmesi için geçmesi gereken zaman aralığı. 90 sn.’lik bu zaman aralığında yönlendirme tablosundaki bir yönlendirme kaydıyla alakalı bir güncelleme olmazsa o kayıt geçersiz olarak isaretlenir. Ardından komsu router’lara bu yönlendirmenin geçersiz olduğu bildirilir.

* **Route Flush Timer:** Bir yönlendirmenin geçersiz olması ve yönlendirme tablosundan kaldırılması için gereken zaman aralığı(240 sn.).

