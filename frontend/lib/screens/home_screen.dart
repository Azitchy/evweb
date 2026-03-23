import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../providers/auth_provider.dart';
import '../providers/wallet_provider.dart';
import '../providers/charging_provider.dart';
import '../providers/notification_provider.dart';
import '../providers/station_provider.dart';
import '../providers/theme_provider.dart';
import 'charging_screen.dart';
import 'wallet_screen.dart';
import 'profile_screen.dart';
import 'history_screen.dart';
import 'login_screen.dart';
import 'station_locator_screen.dart';
import 'subscription_screen.dart';
import 'notification_screen.dart';
import 'payment_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;

  late final List<Widget> _pages;

  @override
  void initState() {
    super.initState();
    _pages = [
      _DashboardPage(onTabChange: (i) => _switchTab(i)),
      const StationLocatorScreen(),
      const ChargingScreen(),
      const WalletScreen(),
      const ProfileScreen(),
    ];
    WidgetsBinding.instance.addPostFrameCallback((_) => _loadData());
  }

  void _loadData() {
    context.read<StationProvider>().fetchStations();
    final auth = context.read<AuthProvider>();
    if (!auth.isLoggedIn) return;
    context.read<WalletProvider>().fetchBalance();
    context.read<ChargingProvider>().fetchActiveSession();
    context.read<NotificationProvider>().fetchUnreadCount();
  }

  /// Tabs that require authentication (Charge, Wallet, Profile)
  static const _authRequiredTabs = {2, 3, 4};

  void _switchTab(int index) {
    if (_authRequiredTabs.contains(index)) {
      final auth = context.read<AuthProvider>();
      if (!auth.isLoggedIn) {
        _showLoginPrompt();
        return;
      }
    }
    setState(() => _currentIndex = index);
  }

  void _showLoginPrompt() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Login Required'),
        content: const Text('You need to log in to access this feature.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(ctx);
              Navigator.of(
                context,
              ).push(MaterialPageRoute(builder: (_) => const LoginScreen()));
            },
            child: const Text('Login'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_currentIndex],
      bottomNavigationBar: NavigationBar(
        selectedIndex: _currentIndex,
        onDestinationSelected: _switchTab,
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.dashboard_outlined),
            selectedIcon: Icon(Icons.dashboard),
            label: 'Home',
          ),
          NavigationDestination(
            icon: Icon(Icons.location_on_outlined),
            selectedIcon: Icon(Icons.location_on),
            label: 'Stations',
          ),
          NavigationDestination(
            icon: Icon(Icons.ev_station_outlined),
            selectedIcon: Icon(Icons.ev_station),
            label: 'Charge',
          ),
          NavigationDestination(
            icon: Icon(Icons.account_balance_wallet_outlined),
            selectedIcon: Icon(Icons.account_balance_wallet),
            label: 'Wallet',
          ),
          NavigationDestination(
            icon: Icon(Icons.person_outlined),
            selectedIcon: Icon(Icons.person),
            label: 'Profile',
          ),
        ],
      ),
    );
  }
}

class _DashboardPage extends StatelessWidget {
  final ValueChanged<int> onTabChange;

  const _DashboardPage({required this.onTabChange});

  void _showLoginRequiredDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Login Required'),
        content: const Text('You need to log in to access this feature.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(ctx);
              Navigator.of(
                context,
              ).push(MaterialPageRoute(builder: (_) => const LoginScreen()));
            },
            child: const Text('Login'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final wallet = context.watch<WalletProvider>();
    final charging = context.watch<ChargingProvider>();
    final notifications = context.watch<NotificationProvider>();
    final themeProvider = context.watch<ThemeProvider>();
    final isLoggedIn = auth.isLoggedIn;

    return Scaffold(
      appBar: AppBar(
        title: Text('Hi, ${auth.user?.name ?? 'Guest'}'),
        actions: [
          // Map icon — opens Google Maps with all stations
          IconButton(
            icon: const Icon(Icons.map_outlined),
            tooltip: 'View Stations on Map',
            onPressed: () async {
              final stations = context.read<StationProvider>().stations;
              final Uri url;
              if (stations.isNotEmpty) {
                final first = stations.first;
                url = Uri.parse(
                  'https://www.google.com/maps/search/EV+charging+station/@${first.latitude},${first.longitude},12z',
                );
              } else {
                url = Uri.parse(
                  'https://www.google.com/maps/search/EV+charging+station/',
                );
              }
              if (await canLaunchUrl(url)) {
                await launchUrl(url, mode: LaunchMode.externalApplication);
              }
            },
          ),
          // Theme toggle
          IconButton(
            icon: Icon(
              themeProvider.isDark
                  ? Icons.light_mode_outlined
                  : Icons.dark_mode_outlined,
            ),
            tooltip: themeProvider.isDark
                ? 'Switch to Light Mode'
                : 'Switch to Dark Mode',
            onPressed: () => themeProvider.toggleTheme(),
          ),
          if (isLoggedIn) ...[
            Stack(
              children: [
                IconButton(
                  icon: const Icon(Icons.notifications_outlined),
                  onPressed: () {
                    Navigator.of(context).push(
                      MaterialPageRoute(
                        builder: (_) => const NotificationScreen(),
                      ),
                    );
                  },
                ),
                if (notifications.unreadCount > 0)
                  Positioned(
                    right: 8,
                    top: 8,
                    child: Container(
                      padding: const EdgeInsets.all(4),
                      decoration: const BoxDecoration(
                        color: Colors.red,
                        shape: BoxShape.circle,
                      ),
                      child: Text(
                        '${notifications.unreadCount}',
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 10,
                        ),
                      ),
                    ),
                  ),
              ],
            ),
            IconButton(
              icon: const Icon(Icons.logout),
              onPressed: () async {
                await context.read<AuthProvider>().logout();
                if (!context.mounted) return;
                Navigator.of(context).pushAndRemoveUntil(
                  MaterialPageRoute(builder: (_) => const HomeScreen()),
                  (_) => false,
                );
              },
            ),
          ] else
            IconButton(
              icon: const Icon(Icons.login),
              onPressed: () {
                Navigator.of(
                  context,
                ).push(MaterialPageRoute(builder: (_) => const LoginScreen()));
              },
            ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await context.read<StationProvider>().fetchStations();
          if (!isLoggedIn) return;
          await wallet.fetchBalance();
          await charging.fetchActiveSession();
          await notifications.fetchUnreadCount();
        },
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            // Wallet Balance Card
            if (isLoggedIn) ...[
              Card(
                color: Theme.of(context).colorScheme.primaryContainer,
                child: Padding(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Wallet Balance',
                        style: Theme.of(context).textTheme.titleMedium,
                      ),
                      const SizedBox(height: 8),
                      Text(
                        '₹ ${wallet.balance.toStringAsFixed(2)}',
                        style: Theme.of(context).textTheme.headlineLarge
                            ?.copyWith(fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 16),
            ],

            // Active Charging Status
            if (isLoggedIn && charging.isCharging) ...[
              Card(
                color: Colors.orange.withValues(alpha: 0.15),
                child: ListTile(
                  leading: const Icon(
                    Icons.bolt,
                    color: Colors.orange,
                    size: 32,
                  ),
                  title: const Text('Charging in Progress'),
                  subtitle: Text(
                    'Started at ${charging.activeSession!.startPercentage.toStringAsFixed(0)}%',
                  ),
                  trailing: const Icon(Icons.arrow_forward_ios, size: 16),
                ),
              ),
              const SizedBox(height: 16),
            ],

            // Quick Actions
            Text(
              'Quick Actions',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.ev_station,
                    label: 'Start\nCharging',
                    color: Colors.green,
                    onTap: () => onTabChange(2),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.location_on,
                    label: 'Find\nStations',
                    color: Colors.orange,
                    onTap: () => onTabChange(1),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.add_card,
                    label: 'Add\nMoney',
                    color: Colors.blue,
                    onTap: () => onTabChange(3),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.payment,
                    label: 'Online\nPayment',
                    color: Colors.teal,
                    onTap: () {
                      if (!isLoggedIn) {
                        _showLoginRequiredDialog(context);
                        return;
                      }
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => const PaymentScreen(),
                        ),
                      );
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.star,
                    label: 'Sub-\nscriptions',
                    color: Colors.amber.shade700,
                    onTap: () {
                      if (!isLoggedIn) {
                        _showLoginRequiredDialog(context);
                        return;
                      }
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => const SubscriptionScreen(),
                        ),
                      );
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _QuickActionCard(
                    icon: Icons.history,
                    label: 'Charging\nHistory',
                    color: Colors.purple,
                    onTap: () {
                      if (!isLoggedIn) {
                        _showLoginRequiredDialog(context);
                        return;
                      }
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => const HistoryScreen(),
                        ),
                      );
                    },
                  ),
                ),
              ],
            ),

            // Charging Stations
            const SizedBox(height: 24),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Charging Stations',
                  style: Theme.of(context).textTheme.titleMedium,
                ),
                TextButton(
                  onPressed: () => onTabChange(1),
                  child: const Text('View All'),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Consumer<StationProvider>(
              builder: (context, stationProvider, _) {
                if (stationProvider.isLoading) {
                  return const Center(
                    child: Padding(
                      padding: EdgeInsets.all(24),
                      child: CircularProgressIndicator(),
                    ),
                  );
                }
                if (stationProvider.stations.isEmpty) {
                  return const Center(
                    child: Padding(
                      padding: EdgeInsets.all(24),
                      child: Text('No stations available'),
                    ),
                  );
                }
                final displayStations = stationProvider.stations;
                return Column(
                  children: displayStations.map((station) {
                    final isOnline = station.status == 'active';
                    return Card(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: ListTile(
                        leading: Icon(
                          Icons.ev_station,
                          color: isOnline ? Colors.green : Colors.grey,
                          size: 28,
                        ),
                        title: Text(
                          station.name,
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Text(station.address),
                        trailing: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 8,
                                vertical: 4,
                              ),
                              decoration: BoxDecoration(
                                color: isOnline
                                    ? Colors.green.shade50
                                    : Colors.grey.shade200,
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Text(
                                station.status.toUpperCase(),
                                style: TextStyle(
                                  fontSize: 11,
                                  fontWeight: FontWeight.bold,
                                  color: isOnline
                                      ? Colors.green.shade700
                                      : Colors.grey.shade700,
                                ),
                              ),
                            ),
                            const SizedBox(width: 8),
                            IconButton(
                              icon: const Icon(
                                Icons.directions,
                                color: Colors.blue,
                              ),
                              tooltip: 'Navigate',
                              onPressed: () async {
                                final url = Uri.parse(
                                  'https://www.google.com/maps/dir/?api=1&destination=${station.latitude},${station.longitude}',
                                );
                                if (await canLaunchUrl(url)) {
                                  await launchUrl(
                                    url,
                                    mode: LaunchMode.externalApplication,
                                  );
                                }
                              },
                            ),
                          ],
                        ),
                      ),
                    );
                  }).toList(),
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}

class _QuickActionCard extends StatelessWidget {
  final IconData icon;
  final String label;
  final Color color;
  final VoidCallback onTap;

  const _QuickActionCard({
    required this.icon,
    required this.label,
    required this.color,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              Icon(icon, size: 32, color: color),
              const SizedBox(height: 8),
              Text(
                label,
                textAlign: TextAlign.center,
                style: Theme.of(context).textTheme.bodySmall,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
