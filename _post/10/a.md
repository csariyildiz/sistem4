
### EDGE

* 462 kurulucak. Restart. cmdden alltaki ilk setuo.
* Yarın exchange cmd'den kurulacak.


```
Add-WindowsFeature ADLDS,Telnet-Client

Setup.exe /Mode:Install /Roles:EdgeTransport /IAcceptExchangeServerLicenseTerms

Get-ServerComponentState
Test-ServiceHealth
Get-TransportAgent
Get-ReciveConnector
Get-ReciveConnector | fl

New-EdgeSubscription –FileName C:\cagri.xml
///// Exchange üzerinde otomatik olarak sendconnector vekimlik denetimi denetimini sağlayacak. Subordinate sertifikaya ben
///// zetebilirsiniz. Export ve import ediyoruz. Bunu diğer exchange makinasında yapacağız. 
///// Buradan aşağısı exchange makinada yapılacak:

New-EdgeSubscription -FileData ([byte[]]$(Get-Content -Path “C:\Temp\edge01.xml” -Encoding Byte -ReadCount 0)) -Site “Default-First-Site-Name”


///// Daha sonra da senkronizasyonu sağlıyoruz.

Start-EdgeSynchronization
```
