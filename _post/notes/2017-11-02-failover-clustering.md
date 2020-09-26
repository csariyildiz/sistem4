---
layout: post
title:  "Failover Clustering"
categories: second
tags: failover-clustering
---

2 adet fiziksel sunucuya (node) sahip bir yapı olsun. Eğer nodelardan biri down olursa, node üzerinde çalışan sanal makinaların (veya rollerin)
diğer node'a aktarılmasına failover clustering denir.

```
Senaryo :   DC
            Storage -> ISCSI Target Kurulumu (storage makinesi depolama alanı olarak kullanılır)
            
            Node 1  | Cluster Üyesi   Failover clustering Kurulumu
            Node 2  | Cluster Üyesi
```

![RA Storage](https://github.com/acsariyildiz/sistem4/blob/gh-pages/images/ra-storage.png?raw=true "RA Storage")

Makinaların hepsi domainde olacak.

* Local Network : Domain networkü temsil eder.
* ISCSI Network : Storage sunucusuyla nodelar arasında ISCSI bağlantısı yapar.
* Cluster-net Network : Network  Nodeların sağlık durumlarını kontrol edecek network yapısı.
* VMs Network : Nodelar üzerindeki sanal makinelerinhostlar ve sanallarla konuşmasını sağlar. (IP adresi atanmaz.)

## ADIMLAR :

* **Failover Clustering Kurulumu:** Node1 ve 2'ye kurulur. (Rol değil featuredır.)
* **ISCSI Target Server Kurulumu:** Storage sunucusuna kurulur. (Rol kısmında, file and storage services altında)
* **Switch Yapılandırma:** Pass
* **ISCSI Initator Servisini Node1/2 Üzerinde Yapılandırma:** (Server işletim sisteminde yerleşik gelir.)
Bu servis istemci gibidir, storage ile nodelar arasında bağlantı sağlar.

Node bilgisayarlardan `iSCSI Initiator Properties> Discover Portal... > Storage IP`'si girilir.

* **ISCSI Target Yapılandırma ve Virtual Disk Oluşturma** (Storage Üzerinde)

ISCSI Server -> Local içinde veya uzak lokasyonlardaki sunuculara ortak depolama alan sağlar. (file server mantığı)

Disk

2 adet disk oluşturulur.

Quorum Disk : Cluster yapılarının olduğu ve nodeların bu diske ulaşarak cluster bilgilerinin tutulduğu database.

Diğer Disk (LUN) : Sanal makine conf bilgileri ve sanal disklerin tutulduğu alan.

```
Server Manager 
> File And Storage S. 
> to create an ISCSI virtual disk, start new .. 
> oluşturduğumuz (eklediğimiz) diski seç
> name quorum
> 1 GB dynamically : fixed seçilirse fiziksel diskte olan alan ayrılır.
> New ISCSI Target

# Her defasında ISCSI target oluşturmaya gerek yok.

> name cluster-target
> Access Server ekranı Add
> Node1,2 (servisler önceden uyandırılmıştı)
> Select from.. node1,2 seçilir. (2. seçenek)
> 2 IQN ismi de gözükmeli > disk ve target oluşturuldu.  ✓
```

ISCSI VD : VHD formatında saklanacak diskler yer alır. (Sanal depolama alanı)

ISCSI Target : ISCSI disklere erişim sağlayacak targetlar bulunur.

LUN disk oluşturma. `vhdx formatında` (Virtual Machine'lerin saklanacağı)

```
Server Manager
> ISCSI VD
> Task
> New ISCSI VD
> Select by value veya custom path
> name LUN
> 400 GB Dynamic
> var olan target
> disk oluştur.
```

### Node1 Üzerinde

ISCSI İnitiator > Targets > Connect > Volume and Devices > Auto conf

(Diğer Nodelarda Da Yapılır)

```
DİSK Manager
Disk > online yapılır > Initialize > Disk1,2 seçilir.
> Disk (quorum)> simple volume > (1021MB)
```

Disk biçimlendirme disk2 içinde yapılır. 

Disk işlemleri tek node'de yapılır.

### Failover Cluster Yapılandırma

```
FC Manager Consolu
> Create Cluster
> Node 1,2 eklenir
> name cluster
> IP gir
```

### Quorum Disk Yapılandırma

16GB lık diski, yapıya quorum disk olarak gösterme işlemi :

```
Sağ Tık
> More Options
> conf. cluster quorum settings
> select quorum witness 
> conf witness disk
> quorum diski seç
```

### Cluster Shared Volume (CSV) Yapılandırma

CSV: Nodeların disklere ortak olarak erişmesini sağlar. (Aynı anda)

```
Failover Cluster Manager
> Disks
> 400GB Storage disk (avaible)
> sağ tık
> add to cluster shared values
```

C:\Cluster Storage'da görünür.

### Failover Cluster Yapısında Sanal Makine Oluşturmak

```
Roles > Sağ Click > New Virtual Machine
```

### Test

Makinelerden biri down edilir. 
