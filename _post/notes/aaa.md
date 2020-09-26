mars.com >> mars.local (sadece iç haberleşme için)

POP3 veya IMAP mailler exchange server dışındaki hizmet alınan servislerdir.

Web, ftp ve mail hizmeti.

DNS'de bir tane CNAME oluşturulmalıdır.(mail.siteadi.com, webmail.siteadi.com)

"New outlook data file" ile mailler için bir veritabanı oluşturulur.

Document\Outlook Files'ın altında bulunur. ".pst" uzantılıdır.

Bu dosyalar yedeklenmelidir. (Mail Server olmamasının dezavantajı, local'de kurulmalı)

User Mailbox diyoruz.

Şifreli olmasına rağmen şifrelere erişilebilir.(outlook mail password viewer)

Kullanıcıların cache'deki hesaplarını(iletişim kurduğu kişiler) görüntülemek için de bir program var.

Cache dosyalarının da yedeği alınmalıdır.

Not: Bu sorunlar local'de mail server olmamasından kaynaklanıyor.

Not: Outlook'da "Remember Password" seçili olmamalı.

Outlook Web Acces (owa) web üzerinden erişim için.

Outlook, mobil, web birbiriyle senkronize olabilir de olmayabilir de =)

Server'da mailin bir kopyası belirli bir süre için saklanabilir. (Outlook Advanced Settings'den)

Otomatik send/receive ayarları default 30 dk'dır.

Şirket bünyesine mail server kursak dahi hosting hizmeti almamız gerekiyor.

Pop3 ve Smtp için CNAME yapması gerekiyor. (mail.mars.com)

username/password/ssl

Biz de statik ip'mizi veriyoruz.

(Relay Hizmeti dış bacağa kadar yapılır. Port forwarding ile iç server'a gönderilir)

Çıkış cihazlarında Nat port forwarding yapmamız gerekiyor.

Cname >> Mx Record >> mail.mars.local


Static Nat(Port forwarding);

	ip nat inside source static tcp 192.168.10.3 110 81.12.80.90 110
	ip nat inside source static tcp 192.168.10.3 587 81.12.80.90 587

Not: Dışarıdan gelen herşey için port forwarding(nat) yapılır.


Mars-DC1 makinası kuruldu. 

Install-WindowsFeature AD-Domain-Services -IncludeManagementTools
Install-ADDSForest -DomainName mars.local

Exchange 2013'ü konsoldan ve web arayüzünden yönetebiliriz.

Office 365.

Dotnet framework 3.5 exchange için gerekli değildir fakat offline kurulumunu bil.

Specify'den D:\\sources\sxs'den iso dosyasının yolunu göstererek framework 3.5'i buradan kurabiliriz.

Dotnet'in update dosyalarını bir iso dosyasında topladık net 462.... isimli update'i kuruyoruz.

ldifde -f kullanicilar.ldf
ldifde -i -f kullanicilar.ldf

Microsoft Exchange Information Store servisi Stop olursa mail trafiği durur.
Bu servis stop edilerek manuel yedekleme alınır. 
Bu işi otomatikleştirmek yazılımlar ile lazım (Acronis, Symantec)

cobianbackup kurduk. Zamanlanmış yedekleme yapmak için.

Exchange bir Back Office uygulamasıdır. (Back Office uygulamalarıyla Exchange ile çalıştım =) )


------------------------------------

09.10.2017

Yeni bir DC kurduk ayarları yaptık.

Firewall'da gerekli portları açık duruma getiren ayarlamaları yapıyoruz. Group Policy Management'dan.

Exc 2016 ve 2013 portları aynıdır.

Bu portlar; 443(TCP HTTPS), 80(TCP HTTP), 143 ve 993 (IMAP), 110(POP3(AL), SMTP(GÖNDER)), 995, 25, 587

Install-WindowsFeature AS-HTTP-Activation, Server-Media-Foundation, NET-Framework-45-Features, RPC-over-HTTP-proxy, RSAT-Clustering, RSAT-Clustering-CmdInterface, RSAT-Clustering-Mgmt, RSAT-Clustering-PowerShell, Web-Mgmt-Console, WAS-Process-Model, Web-Asp-Net45, Web-Basic-Auth, Web-Client-Auth, Web-Digest-Auth, Web-Dir-Browsing, Web-Dyn-Compression, Web-Http-Errors, Web-Http-Logging, Web-Http-Redirect, Web-Http-Tracing, Web-ISAPI-Ext, Web-ISAPI-Filter, Web-Lgcy-Mgmt-Console, Web-Metabase, Web-Mgmt-Console, Web-Mgmt-Service, Web-Net-Ext45, Web-Request-Monitor, Web-Server, Web-Stat-Compression, Web-Static-Content, Web-Windows-Auth, Web-WMI, Windows-Identity-Foundation, RSAT-ADDS

Ağ kaydı ile Mx kaydı ilişkilendirilir.(New Mx, New Host)

Mx'den geriye dönük host name çözümlemesi yapılabilir.

Adres çözümlemeyi hızlandırıyoruz.

srv kayıtları ilgili servise daha hızlı erişmek için.(_autodiscovery'i tcp'nin altına kuruyoruz)


--------------------------------

16.10.2007

Master Roller

DC'yi kaybettik =)

# Master Operation Role: 

Forest:
	-Şema Master
	-Domain Naming Master

Domain:
	-PDC(Primary Domain Controller)
	-RID Master(Relayive Identity)
	-Infrastructure Master

Root Domain'de bunların 5'i birden gelir.

Şema'nın bilgilendirme amacıyla kullanılır.

Elimizde Şema olmazsa proje çalışmaz, yönetemeyiz. Master Rollerden en önemlisi Şema'dır.

PDC; Doğrulama amacıyla kullanılır. Kimlik denetimini yapar. RID Master ile beraber yapar.

RID; Active Directory'e eklenen her objeye bir SID numarası atanır.

Infrastructure; Alt yapı bilgisi vardır. Gideceğimiz yerin bilgisini tutar.

Master roller ilk kurulan DC'dedir. Roller DC'ler arasında dağıtılabilir.

Silinen DC'ye ait Master Rollerin birer kopyaları vardır.


DC->Tools->Users and computers->sun.local->sağ tık->operations master

İlk DC'yi kaybetmeseydik rolleri buradan dağıtabilirdik. Change ile değiştirebiliyoruz.

FSMO(Flexible Single Master Operation)

cmd>netdom query fsmo //rollerin hangi DC'de olduğunu görüyoruz

Tools->AD Domain and Trust->sağ tık->operations master
Bu konsoldan sadece Domaing Naming Master görüntülenebilir.

Şema Master konsolu gizlenmiştir.

run>regsvr32 schmmgmt.dll

mmc>File->add .. ->schema management buradan eklenir.

Active Directory Schema->sağ tık->operational master diyoruz

Bu da olmadı =)

Komut ile yapıyoruz.

cmd>ntdsutil

//AD ile ilgili her şeyi buradan yapabiliriz.
Rolleri de buradan aktarabiliriz.

ntdsutil: roles
//Transfer komutu DC yokken kullanılamaz. İki DC'de online olmaz zorunda.

Seize komutu ile zorlayarak transfer yapabiliriz.

Öncelikle rolü aktaracağımız DC'ye connect olmalıyız.

:connections
:connect to server sun-dc2
:quit
:seize rid master 


:? //domain naming master rolünü transfer edeceğiz
:seize naming master

//Güvenlik ve çökmelere karşı roller dağıtılıyor

:seize schema master //bu rolü de DC 2'ye dağıtıyoruz

5 tane rolü aktarmış oluyoruz.

cmd>netdom query fsmo //rollerin hangi DC'de olduğunu görüyoruz

Restart

Toolds->Users and Computers->Domain controllers

Aynı Site içerisindeki DC'ler birbiriyle replike olurlar

Toolds->Site and Services

"Sun-DC1 NTDS settings" delete edilir.

run>dnsmgmt.msc

sun.local->_tcp, _udp 

sun.local->sağ tık properties->name servers->DC1 remove edilir.

Reverse Lookup Zone->sağ tık properties->name servers DC1 kaldırılır.

DNS adresleri de değiştirilmeli.

Özet;

fsmo transfer/seize

ntdsutil
	roles
	connections
		connect to server dc2
		quit
	transfer(2 makine online)/seize pdc
	transfer/seize rid master
	transfer/seize infrastucture master
	transfer/seize naming master
	transfer/seize schema master
	quit
quit


# Migration: Server 12'den 16'ya yükseltme işlemi, tersi olmuyor

2012 AD -> 2012 R2 AD -> 2016 AD geçiş yapılabilir

EXC 2010 -> 2013 -> 2016'ya geçiş yapılabilir. Atlayarak geçiş olmuyor.

Migration'dan önce yedeklemeler tam olarak yapılmalı.

2012 R2 AD ve Exc 2013'ü 2016 AD'ye taşıyacağız. AD'nin tamamını 2016 üzerinden yönetmiş olacağız.

2012 R2 -> 2016 AD Migration yapacağız

-Server 2016'yı domaine kat
-AD DS'yi yükle
-Promote et
-FSMO taşı
-2012 Serve De-Promote et
-AD DS, DNS uninstall
-Workgroup'a al

FSMO taşıma;

cmd>ntdsutil
	roles
	connections
		connect to server sun-dc3
		quit
	transfer pdc
	transfer rid master
	transfer infrastucture master
	transfer naming master
	transfer schema master
	quit
quit


--------------------------

17.10.2017

Task 1:

-IP
-CompName
-wf.msc //file and sharing

Task 2:

xxx-srv1 sunucusuna remote access tolü yüklenip nat konfigürasyonu yapın

Task 3:

xxx-dc1 sunucusuna ADDS yüklenip promote edin


--------------------------

20.10.2017


EDGE 

edge fw in/out 25,587,50389,50636,3389,23









mars.com >> mars.local (sadece iç haberleşme için)

POP3 veya IMAP mailler exchange server dışındaki hizmet alınan servislerdir.

Web, ftp ve mail hizmeti.

DNS'de bir tane CNAME oluşturulmalıdır.(mail.siteadi.com, webmail.siteadi.com)

"New outlook data file" ile mailler için bir veritabanı oluşturulur.

Document\Outlook Files'ın altında bulunur. ".pst" uzantılıdır.

Bu dosyalar yedeklenmelidir. (Mail Server olmamasının dezavantajı, local'de kurulmalı)

User Mailbox diyoruz.

Şifreli olmasına rağmen şifrelere erişilebilir.(outlook mail password viewer)

Kullanıcıların cache'deki hesaplarını(iletişim kurduğu kişiler) görüntülemek için de bir program var.

Cache dosyalarının da yedeği alınmalıdır.

Not: Bu sorunlar local'de mail server olmamasından kaynaklanıyor.

Not: Outlook'da "Remember Password" seçili olmamalı.

Outlook Web Acces (owa) web üzerinden erişim için.

Outlook, mobil, web birbiriyle senkronize olabilir de olmayabilir de =)

Server'da mailin bir kopyası belirli bir süre için saklanabilir. (Outlook Advanced Settings'den)

Otomatik send/receive ayarları default 30 dk'dır.

Şirket bünyesine mail server kursak dahi hosting hizmeti almamız gerekiyor.

Pop3 ve Smtp için CNAME yapması gerekiyor. (mail.mars.com)

username/password/ssl

Biz de statik ip'mizi veriyoruz.

(Relay Hizmeti dış bacağa kadar yapılır. Port forwarding ile iç server'a gönderilir)

Çıkış cihazlarında Nat port forwarding yapmamız gerekiyor.

Cname >> Mx Record >> mail.mars.local


Static Nat(Port forwarding);

	ip nat inside source static tcp 192.168.10.3 110 81.12.80.90 110
	ip nat inside source static tcp 192.168.10.3 587 81.12.80.90 587

Not: Dışarıdan gelen herşey için port forwarding(nat) yapılır.


Mars-DC1 makinası kuruldu. 

Install-WindowsFeature AD-Domain-Services -IncludeManagementTools
Install-ADDSForest -DomainName mars.local

Exchange 2013'ü konsoldan ve web arayüzünden yönetebiliriz.

Office 365.

Dotnet framework 3.5 exchange için gerekli değildir fakat offline kurulumunu bil.

Specify'den D:\\sources\sxs'den iso dosyasının yolunu göstererek framework 3.5'i buradan kurabiliriz.

Dotnet'in update dosyalarını bir iso dosyasında topladık net 462.... isimli update'i kuruyoruz.

ldifde -f kullanicilar.ldf
ldifde -i -f kullanicilar.ldf

Microsoft Exchange Information Store servisi Stop olursa mail trafiği durur.
Bu servis stop edilerek manuel yedekleme alınır. 
Bu işi otomatikleştirmek yazılımlar ile lazım (Acronis, Symantec)

cobianbackup kurduk. Zamanlanmış yedekleme yapmak için.

Exchange bir Back Office uygulamasıdır. (Back Office uygulamalarıyla Exchange ile çalıştım =) )


------------------------------------

09.10.2017

Yeni bir DC kurduk ayarları yaptık.

Firewall'da gerekli portları açık duruma getiren ayarlamaları yapıyoruz. Group Policy Management'dan.

Exc 2016 ve 2013 portları aynıdır.

Bu portlar; 443(TCP HTTPS), 80(TCP HTTP), 143 ve 993 (IMAP), 110(POP3(AL), SMTP(GÖNDER)), 995, 25, 587

Install-WindowsFeature AS-HTTP-Activation, Server-Media-Foundation, NET-Framework-45-Features, RPC-over-HTTP-proxy, RSAT-Clustering, RSAT-Clustering-CmdInterface, RSAT-Clustering-Mgmt, RSAT-Clustering-PowerShell, Web-Mgmt-Console, WAS-Process-Model, Web-Asp-Net45, Web-Basic-Auth, Web-Client-Auth, Web-Digest-Auth, Web-Dir-Browsing, Web-Dyn-Compression, Web-Http-Errors, Web-Http-Logging, Web-Http-Redirect, Web-Http-Tracing, Web-ISAPI-Ext, Web-ISAPI-Filter, Web-Lgcy-Mgmt-Console, Web-Metabase, Web-Mgmt-Console, Web-Mgmt-Service, Web-Net-Ext45, Web-Request-Monitor, Web-Server, Web-Stat-Compression, Web-Static-Content, Web-Windows-Auth, Web-WMI, Windows-Identity-Foundation, RSAT-ADDS

Ağ kaydı ile Mx kaydı ilişkilendirilir.(New Mx, New Host)

Mx'den geriye dönük host name çözümlemesi yapılabilir.

Adres çözümlemeyi hızlandırıyoruz.

srv kayıtları ilgili servise daha hızlı erişmek için.(_autodiscovery'i tcp'nin altına kuruyoruz)


--------------------------------

16.10.2007

Master Roller

DC'yi kaybettik =)

# Master Operation Role: 

Forest:
	-Şema Master
	-Domain Naming Master

Domain:
	-PDC(Primary Domain Controller)
	-RID Master(Relayive Identity)
	-Infrastructure Master

Root Domain'de bunların 5'i birden gelir.

Şema'nın bilgilendirme amacıyla kullanılır.

Elimizde Şema olmazsa proje çalışmaz, yönetemeyiz. Master Rollerden en önemlisi Şema'dır.

PDC; Doğrulama amacıyla kullanılır. Kimlik denetimini yapar. RID Master ile beraber yapar.

RID; Active Directory'e eklenen her objeye bir SID numarası atanır.

Infrastructure; Alt yapı bilgisi vardır. Gideceğimiz yerin bilgisini tutar.

Master roller ilk kurulan DC'dedir. Roller DC'ler arasında dağıtılabilir.

Silinen DC'ye ait Master Rollerin birer kopyaları vardır.


DC->Tools->Users and computers->sun.local->sağ tık->operations master

İlk DC'yi kaybetmeseydik rolleri buradan dağıtabilirdik. Change ile değiştirebiliyoruz.

FSMO(Flexible Single Master Operation)

cmd>netdom query fsmo //rollerin hangi DC'de olduğunu görüyoruz

Tools->AD Domain and Trust->sağ tık->operations master
Bu konsoldan sadece Domaing Naming Master görüntülenebilir.

Şema Master konsolu gizlenmiştir.

run>regsvr32 schmmgmt.dll

mmc>File->add .. ->schema management buradan eklenir.

Active Directory Schema->sağ tık->operational master diyoruz

Bu da olmadı =)

Komut ile yapıyoruz.

cmd>ntdsutil

//AD ile ilgili her şeyi buradan yapabiliriz.
Rolleri de buradan aktarabiliriz.

ntdsutil: roles
//Transfer komutu DC yokken kullanılamaz. İki DC'de online olmaz zorunda.

Seize komutu ile zorlayarak transfer yapabiliriz.

Öncelikle rolü aktaracağımız DC'ye connect olmalıyız.

:connections
:connect to server sun-dc2
:quit
:seize rid master 


:? //domain naming master rolünü transfer edeceğiz
:seize naming master

//Güvenlik ve çökmelere karşı roller dağıtılıyor

:seize schema master //bu rolü de DC 2'ye dağıtıyoruz

5 tane rolü aktarmış oluyoruz.

cmd>netdom query fsmo //rollerin hangi DC'de olduğunu görüyoruz

Restart

Toolds->Users and Computers->Domain controllers

Aynı Site içerisindeki DC'ler birbiriyle replike olurlar

Toolds->Site and Services

"Sun-DC1 NTDS settings" delete edilir.

run>dnsmgmt.msc

sun.local->_tcp, _udp 

sun.local->sağ tık properties->name servers->DC1 remove edilir.

Reverse Lookup Zone->sağ tık properties->name servers DC1 kaldırılır.

DNS adresleri de değiştirilmeli.

Özet;

fsmo transfer/seize

ntdsutil
	roles
	connections
		connect to server dc2
		quit
	transfer(2 makine online)/seize pdc
	transfer/seize rid master
	transfer/seize infrastucture master
	transfer/seize naming master
	transfer/seize schema master
	quit
quit


# Migration: Server 12'den 16'ya yükseltme işlemi, tersi olmuyor

2012 AD -> 2012 R2 AD -> 2016 AD geçiş yapılabilir

EXC 2010 -> 2013 -> 2016'ya geçiş yapılabilir. Atlayarak geçiş olmuyor.

Migration'dan önce yedeklemeler tam olarak yapılmalı.

2012 R2 AD ve Exc 2013'ü 2016 AD'ye taşıyacağız. AD'nin tamamını 2016 üzerinden yönetmiş olacağız.

2012 R2 -> 2016 AD Migration yapacağız

-Server 2016'yı domaine kat
-AD DS'yi yükle
-Promote et
-FSMO taşı
-2012 Serve De-Promote et
-AD DS, DNS uninstall
-Workgroup'a al

FSMO taşıma;

cmd>ntdsutil
	roles
	connections
		connect to server sun-dc3
		quit
	transfer pdc
	transfer rid master
	transfer infrastucture master
	transfer naming master
	transfer schema master
	quit
quit


--------------------------

17.10.2017

Task 1:

-IP
-CompName
-wf.msc //file and sharing

Task 2:

xxx-srv1 sunucusuna remote access tolü yüklenip nat konfigürasyonu yapın

Task 3:

xxx-dc1 sunucusuna ADDS yüklenip promote edin


--------------------------

20.10.2017


EDGE 

edge fw in/out 25,587,50389,50636,3389,23









