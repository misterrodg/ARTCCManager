# ARTCC Manager

ARTCC Manager is a web application to manage data and build files for VATSIM.
It is the result of my experience as an FE off and on since 2010.
I noticed that, as FEs come and go, an ARTCC may swing wildly from having
ever-current data with advanced maps, to rare updates with only basic data.
That experience is primarily dependent on the FE's familiarity with the various
data products, along with their own skillset to work with those products.
In order to narrow that skillset gap and allow for updates to be pushed even
during FE absences, I developed ARTCC Manager.

## Features

-   Manages ARTCC data:
    -   Imports FAA CIFP and NASR data
    -   Builds Airport, Runway, Nav data from NASR
    -   Builds SID/STAR/IAP data from CIFP as Diagrams/Vidmaps
    -   Builds M/P/R/W (Restrictive) Airspace from CIFP
-   Supports VRC, vSTARS and vERAM controller clients
-   Supports TWRTrainer as a training client

## Roadmap

-   [x] Initial Functionality
    -   [x] FAA Data Import/Processing
        -   [x] CIFP/NASR Data
        -   [x] Preferred Routes
        -   [x] Coded Departure Routes
        -   [ ] LOA Route Management
    -   [x] VRC File Build Capability
    -   [x] vSTARS File Build Capability
    -   [x] vERAM File Build Capability
-   [ ] Advanced Functionality
    -   [x] vSTARS FAA-Compliant MSAW Volumes
    -   [x] Runway/Config Manager
    -   [ ] Ad Hoc Vidmap Creator/Editor
-   [x] Admin Panel
-   [ ] EuroScope Functionality
    -   [ ] ES File Build Capability
    -   [ ] ES Simulation File Build Capability
-   [ ] CRC Functionality
    -   [ ] GeoJSON File Build Capability

## Frontend

A limited frontend has been included to help people get started.
I'm more of a database/backend developer, so the UI and React code may be
sub-optimal. I appreciate your grace in this area.
Because of the variation in the auth implementations across ARTCCs,
the user/auth management is internal to this web application.

## Prefer a Desktop Application?

Check out [FEBuddy](https://github.com/Nikolai558/FE-BUDDY).
The functionality of ARTCC Manager will also be ported there.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

The included font is licensed under the SIL Open Font License, Version 1.1.
This license is available with a FAQ at [SIL.org](http://scripts.sil.org/OFL).
