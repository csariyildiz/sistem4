
## Dikkat Çeken Noktalar

* User eklerken `Active Directory Administrative Center`'a yönlendiriyor. Biz konsolu kullanıyorduk.
* `Clipboard` , `Type clipboard text` - Bu sayede sanal bilgisayara kopyala-yapıştır yapabiliyoruz.
* APP1 bilgisayarının CORP Domain'e Alınması esnasında  `nslookup` doğru yanıt vermeyince DC1'de `reverse-zone` ve `PTR` oluşturuldu.
Bu kısım dokümanda yoktu.
* CLIENT1 Windows 8 Enterprise yerine Windows 10 kuruldu.


```
10.0.0.0/24       Internet  LanX2
131.107.0.0/24    Corpnet   LanX
```



## DC1

* Master Hard Disk'yen Differencing olarak oluşturuldu. LanX seçildi.
* TCP/IP 

### DC1 Servisleri
* `corp.contoso.com` Active Directory Domain Services (AD DS) Domaini için `domain controller`
* `corp.contoso.com` DNS Domaini için `DNS Server`
* `Corpnet subnet`'i için `DHCP Server`

### DC1 Konfigürasyonlar

* İşletim Sisteminin Kurulumu (Windows Server 2012)
* TCP/IP Konfigürasyonu
* Active Directory ve DNS Kurulumu
* DHCP Kurulumu
* Active Directory için Kullanıcı Hesabı

### TCP/IP Konfigürasyonu

* Aşağıdaki gibi powershell üzerinden konfigürasyon yapıldı.

```
New-NetIPAddress 10.0.0.1 -InterfaceAlias "Ethernet" -PrefixLength 24
Set-DnsClientServerAddress -InterfaceAlias "Ethernet" -ServerAddresses 127.0.0.1
Rename-Computer DC1
Restart-Computer
```

* Bu komutlarda `Ethernet` her bilgisayar için farklı olabilir. Kontrol edildi.
* Konfigürasyon komutlar kullanılarak yapıldı.

### Domain Controller ve DNS Server Konfigürasyonu

* Active Directory Domain Services rol olarak kurulumu
* Promote, Add a new forest, `corp.contoso.com`
* Aşağıdaki gibi powershell üzerinden konfigürasyon yapıldı.

```
Install-WindowsFeature AD-Domain-Services -IncludeManagementTools
Install-ADDSForest -DomainName corp.contoso.com 
```

* `CORP\Administrator` hesabı ile giriş yapıldı.


### DHCP Kurulumu

```
Role kurulumu.
New Scope -> Corpnet
 Start IP Address  : 10.0.0.100
 End IP Address : 10.0.0.200
 Subnet Mask : 255.255.255.0.

```


```
Install-WindowsFeature DHCP -IncludeManagementTools
Add-DhcpServerv4Scope -name "Corpnet" -StartRange 10.0.0.100 -EndRange 10.0.0.200 -SubnetMask 255.255.255.0
Set-DhcpServerv4OptionValue -DnsDomain corp.contoso.com -DnsServer 10.0.0.1
Add-DhcpServerInDC -DnsName dc1.corp.contoso.com 
```

### Active Directory için Kullanıcı Hesabı

* User1 kullanıcısı oluşturuldu.
* Domain Admins ve Enterprise Admins gruplarına üye yapıldı.
* Görsel arayüz ile yapıldı.

```
New-ADUser -SamAccountName User1 -AccountPassword (read-host "Set user password" -
assecurestring) -name "User1" -enabled $true -PasswordNeverExpires $true -
ChangePasswordAtLogon $false

Add-ADPrincipalGroupMembership -Identity
"CN=User1,CN=Users,DC=corp,DC=contoso,DC=com" -MemberOf "CN=Enterprise
Admins,CN=Users,DC=corp,DC=contoso,DC=com","CN=Domain
Admins,CN=Users,DC=corp,DC=contoso,DC=com" 
```

## APP1

* İşletim Sisteminin Kurulumu (Windows Server 2012)
* TCP/IP Konfigürasyonu
* APP1 bilgisayarının CORP Domain'e Alınması
* APP1 bilgisayarına IIS Rolünün Kurulması
* Paylaşılan Klasör Oluşturulması


### TCP/IP Konfigürasyonu

```
New-NetIPAddress 10.0.0.3 -InterfaceAlias "Ethernet" -PrefixLength 24
Set-DnsClientServerAddress -InterfaceAlias "Ethernet" -ServerAddresses 10.0.0.1 
```

### APP1 bilgisayarının CORP Domain'e Alınması

* `nslookup` doğru yanıt vermeyince `reverse-zone` ve `PTR` oluşturuldu.

```
Add-Computer -NewName APP1 -DomainName corp.contoso.com
Restart-Computer 
```

### APP1 bilgisayarında Paylaşılan Klasör Oluşturulması

```
New-Item -path c:\files -type directory
Write-Output "This is a shared file." | out-file c:\files\example.txt
New-SmbShare -name files -path c:\files -changeaccess CORP\User1 
```

## CLIENT1

### CLIENT1 Konfigürasyonlar

* İşletim Sisteminin Kurulumu (Windows 10)
* CLIENT1'in CORP Domain'e Eklenmesi (corp.contoso.com)
* Corpnet Ağında Intranet Kaynaklarına Erişimin Test Edilmesi

```
Add-Computer -DomainName corp.contoso.com
Restart-Computer 
```



